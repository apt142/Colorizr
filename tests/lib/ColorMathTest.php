<?php
/**
 * ColorMathTest
 *
 * PHP version 5.3
 *
 * @category Colorizr
 * @package default
 * @author   Cris Bettis <apt142@apartment142.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @link     http://apartment142.com
 */

namespace tests\lib;

use Colorizr\lib\ColorMath;

/**
 * Class ColorMathTest
 *
 * What does this class do?
 *
 * @category tests\lib
 * @package default
 * @author   Cris Bettis <apt142@apartment142.com>
 * @license  CC BY-NC-SA http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @link     http://apartment142.com
 */
class ColorMathTest extends \PHPUnit_Framework_TestCase {

    public function dataTestSet() {
        return array(
            array(
                '#fff',
                array(255, 255, 255)
            ),
            array(
                '#000000',
                array(0, 0, 0)
            ),
            array(
                'ff0000',
                array(255, 0, 0)
            )
        );
    }

    /**
     * Test the set command
     *
     * @dataProvider dataTestSet
     */
    public function testSet($colorString, $colorArray) {
        $colorMath = new ColorMath();

        $color = $colorMath->set($colorString);
        $this->assertTrue(
            $color->red == $colorArray[0]
            && $color->green == $colorArray[1]
            && $color->blue == $colorArray[2]
        );
    }

    /**
     * Test the darken command
     */
    public function testDarken() {
        $colorMath = new ColorMath();
        $colorMath->set('#fff');
        $color = $colorMath->darken(10);
        $this->assertTrue(
            $color->red == 230
            && $color->green == 230
            && $color->blue == 230
        );
    }

    /**
     * Test the lighten command
     */
    public function testLighten() {
        $colorMath = new ColorMath();
        $colorMath->set('#ccc');
        $color = $colorMath->lighten(10);
        $this->assertTrue(
            $color->red == 224
            && $color->green == 224
            && $color->blue == 224
        );
    }

    /**
     * Test the greyscale command
     */
    public function testGreyscale() {
        $colorMath = new ColorMath();
        $colorMath->set('#FA3');
        $color = $colorMath->greyscale();
        $this->assertTrue(
            $color->red == 182
            && $color->green == 182
            && $color->blue == 182
        );
    }

    /**
     * Test the saturate command
     */
    public function testSaturate() {
        $colorMath = new ColorMath();
        $colorMath->set('#FA3');
        $color = $colorMath->saturate(50);
        $this->assertTrue(
            $color->red == 219
            && $color->green == 176
            && $color->blue == 117
        );
    }

    /**
     * Test the saturate command
     */
    public function testDesaturate() {
        $colorMath = new ColorMath();
        $colorMath->set('#FA3');
        $color = $colorMath->desaturate(50);
        $this->assertTrue(
            $color->red == 146
            && $color->green == 188
            && $color->blue == 248
        );
    }

    /**
     * Test the multiply command
     */
    public function testMultiply() {
        $colorMath = new ColorMath();
        $colorMath->set('#ff6600');
        $color = $colorMath->multiply('#999999');
        $this->assertSame('#993d00', $color->toHex());
    }

    /**
     * Test the multiply command
     */
    public function testScreen() {
        $colorMath = new ColorMath();
        $colorMath->set('#ff6600');
        $color = $colorMath->screen('#999999');
        $this->assertSame('#ffc299', $color->toHex());
    }

    /**
     * Test the multiply command
     */
    public function testOverlay() {
        $colorMath = new ColorMath();
        $colorMath->set('#ff6600');
        $color = $colorMath->overlay('#999999');
        $this->assertSame('#ff7a00', $color->toHex());

        $color = $colorMath->overlay('#0000ff');
        $this->assertSame('#ff0000', $color->toHex());

        $color = $colorMath->overlay('#333333');
        $this->assertSame('#ff2900', $color->toHex());

        $color = $colorMath->overlay('#00ff00');
        $this->assertSame('#ffcc00', $color->toHex());
    }

    /**
     * Test the complementary
     */
    public function testComplimentary() {
        $colorMath = new ColorMath();
        $colorMath->set('#ff6600');
        $color = $colorMath->complementary();
        $this->assertSame('#0099ff', $color->toHex());
    }

}
