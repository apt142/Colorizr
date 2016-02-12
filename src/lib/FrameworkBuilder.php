<?php
/**
 * FrameworkBuilder
 *
 * PHP version 5.3
 *
 * @category Graphite
 * @package  Core
 * @author   Cris Bettis <cris.bettis@bettercarpeople.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @link     http://g.lonefry.com
 */

namespace Colorizr\lib;

use Colorizr\models\Color;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request as Request;
use Colorizr\lib\ThemeMath;
use Leafo\ScssPhp\Compiler;

/**
 * Class FrameworkBuilder
 *
 * What does this class do?
 *
 * @category Colorizr\lib
 * @package  Core
 * @author   Cris Bettis <cris.bettis@bettercarpeople.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @link     http://g.lonefry.com
 */
class FrameworkBuilder {
    /** @var null|\Silex\Application $app */
    protected $app  = null;

    // File location variables
    protected $importPath  = '';
    protected $savePath    = 'stylesheets/other/';
    protected $fileName    = 'stylesheet.css';
    protected $varTemplate = '';

    // Stylesheet Options
    /** @var Color[] $palette */
    protected $palette      = array();
    /** @var Color[] $greyScale */
    protected $greyScale    = array();
    protected $font         = array();
    protected $presentation = array();
    protected $options      = array();

    protected $uniqueId = '';

    // Defaults
    protected $defaultFont = '"Helvetica Neue", Helvetica, Arial, sans-serif';

    /**
     * Constructor
     *
     * @param Application $app Application object
     *
     */
    public function __construct(Application $app) {
        $this->app = $app;
    }

    /**
     * Returns palette
     *
     * @return \Colorizr\models\Color[]
     */
    public function getPalette() {
        return $this->palette;
    }

    /**
     * Builds out the color palette set
     *
     * @param Request $request
     *
     * @return \Colorizr\models\Color[]
     */
    protected function buildPalette(Request $request) {
        $defaults = ThemeMath::getDefaults();

        $primary = $request->request->get('primary', $defaults['primary']);

        $themeMath = new ThemeMath();

        $palette   = $themeMath->buildActionPalette($primary);

        // If we were passed other roles, let's overwrite our defaults
        foreach ($palette as $role => $colorSwatch) {
            $color = $request->request->get($role, $colorSwatch);
            if ($color !== null) {
                $palette[$role] = new Color($color);
            }
        }

        return $palette;
    }

    /**
     * Builds the grey scale palette
     *
     * @return \Colorizr\models\Color[]
     */
    protected function buildGreyScale() {
        $themeMath = new ThemeMath();

        return $themeMath->buildGreyPalette();
    }

    /**
     * Build the basic font/readability options
     *
     * @param \Symfony\Component\HttpFoundation\Request $request Request object
     *
     * @return array
     */
    protected function buildFontOptions(Request $request) {
        $greys = $this->buildGreyScale();

        $bodyColor = $request->request->get('body-color', '#fff');
        $textColor = $request->request->get('text-color', '#333');

        $fonts = $fonts = ConfigLoader::loadConfig('fonts');
        $fontFamilyIndex = $request->request->get('font-family', 0);
        $headerFamilyIndex = $request->request->get('heading-family', 0);

        return array(
            'family'         => $fonts[$fontFamilyIndex],
            'base_size'      => $request->request->get('font-size', '14px'),
            'heading_family' => $fonts[$headerFamilyIndex],
            'body_color'     => new Color($bodyColor),
            'text_color'     => new Color($textColor)
        );
    }

    /**
     * Build the basic presentation options
     *
     * @param \Symfony\Component\HttpFoundation\Request $request Request object
     *
     * @return array
     */
    protected function buildPresentationOptions(Request $request) {
        return array(
            'border_radius' => $request->request->get('border-radius', '4px')
        );
    }

    /**
     * Build out the option set
     *
     * @param Request $request
     *
     * @return array
     */
    protected function getOptions(Request $request) {
        $this->palette      = $this->buildPalette($request);
        $this->greyScale    = $this->buildGreyScale();
        $this->font         = $this->buildFontOptions($request);
        $this->presentation = $this->buildPresentationOptions($request);

        $this->options = array(
            'palette'      => $this->palette,
            'greyScale'    => $this->greyScale,
            'font'         => $this->font,
            'presentation' => $this->presentation
        );
        $this->setUniqueId($this->options);

        return $this->options;
    }


    /**
     * Builds the css and returns the contents
     *
     * @param Request $request
     *
     * @return string
     */
    public function build(Request $request) {
        // Build variables for the template into an array
        $options  = $this->getOptions($request);
        $filePath = $this->getFilePath() . '/' . $this->fileName;

        // If CSS does not exist, build it.
        if (!file_exists($filePath)) {
            // Use those variables to build a variable file and set options for building the css
            $sass = $this->createVariableFile($options);
            $css = $this->compile($sass);

            $this->save($css);
        }

        // Return CSS
        return $filePath;
    }

    /**
     * Compiles sass
     *
     * @param string $sassCode Sass Code to compile
     *
     * @return mixed
     */
    protected function compile($sassCode) {
        $scss = new Compiler();
        $scss->setImportPaths($this->importPath);

        return $scss->compile($sassCode);
    }

    /**
     * Creates a Sass variable file for this.
     *
     * @param array $options
     *
     * @return string location
     */
    public function createVariableFile(array $options) {
        $contents = $this->app['twig']->render(
            $this->varTemplate,
            $options
        );

        return $contents;
    }

    /**
     * Returns a has of the option set to use as a unique id for caching.
     *
     * @param array $options Options
     *
     * @return string
     */
    protected function setUniqueId($options) {
        $this->uniqueId = md5(serialize($options));
    }

    /**
     * Saves the css to disk
     *
     * @param string $contents Contents of the CSS
     *
     * @return bool
     */
    protected function save($contents) {
        $path = $this->createPath($this->uniqueId);
        file_put_contents(
            WEB_ROOT . '/' . $path . '/' . $this->fileName,
            $contents
        );
        chmod(WEB_ROOT . '/' . $path . '/' . $this->fileName, 0755);
    }

    /**
     * Creates the file structure to save the css
     *
     * @return string
     */
    protected function createPath() {
        $path = $this->getFilePath();
        if (!is_dir(WEB_ROOT . '/' . $path)) {
            mkdir(WEB_ROOT . '/' . $path, 0777, true);
        }

        return $path;
    }

    /**
     * Gets the file path this item should live at.
     *
     * @return string
     */
    protected function getFilePath() {
        $dirs = str_split($this->uniqueId, 4);
        $path =  $this->savePath . implode('/', $dirs);
        return $path;
    }
}
