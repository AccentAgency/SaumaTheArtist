<?php

/**
 * PKCS#8 Formatted DH Key Handler
 *
 * PHP version 5
 *
 * Processes keys with the following headers:
 *
 * -----BEGIN ENCRYPTED PRIVATE KEY-----
 * -----BEGIN PRIVATE KEY-----
 * -----BEGIN PUBLIC KEY-----
 *
 * @category  Crypt
 * @package   DH
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2015 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 */
namespace WPMailSMTP\Vendor\phpseclib3\Crypt\DH\Formats\Keys;

use WPMailSMTP\Vendor\phpseclib3\Math\BigInteger;
use WPMailSMTP\Vendor\phpseclib3\Crypt\Common\Formats\Keys\PKCS8 as Progenitor;
use WPMailSMTP\Vendor\phpseclib3\File\ASN1;
use WPMailSMTP\Vendor\phpseclib3\File\ASN1\Maps;
use WPMailSMTP\Vendor\phpseclib3\Common\Functions\Strings;
/**
 * PKCS#8 Formatted DH Key Handler
 *
 * @package DH
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
abstract class PKCS8 extends \WPMailSMTP\Vendor\phpseclib3\Crypt\Common\Formats\Keys\PKCS8
{
    /**
     * OID Name
     *
     * @var string
     * @access private
     */
    const OID_NAME = 'dhKeyAgreement';
    /**
     * OID Value
     *
     * @var string
     * @access private
     */
    const OID_VALUE = '1.2.840.113549.1.3.1';
    /**
     * Child OIDs loaded
     *
     * @var bool
     * @access private
     */
    protected static $childOIDsLoaded = \false;
    /**
     * Break a public or private key down into its constituent components
     *
     * @access public
     * @param string $key
     * @param string $password optional
     * @return array
     */
    public static function load($key, $password = '')
    {
        if (!\WPMailSMTP\Vendor\phpseclib3\Common\Functions\Strings::is_stringable($key)) {
            throw new \UnexpectedValueException('Key should be a string - not a ' . \gettype($key));
        }
        $isPublic = \strpos($key, 'PUBLIC') !== \false;
        $key = parent::load($key, $password);
        $type = isset($key['privateKey']) ? 'privateKey' : 'publicKey';
        switch (\true) {
            case !$isPublic && $type == 'publicKey':
                throw new \UnexpectedValueException('Human readable string claims non-public key but DER encoded string claims public key');
            case $isPublic && $type == 'privateKey':
                throw new \UnexpectedValueException('Human readable string claims public key but DER encoded string claims private key');
        }
        $decoded = \WPMailSMTP\Vendor\phpseclib3\File\ASN1::decodeBER($key[$type . 'Algorithm']['parameters']->element);
        if (empty($decoded)) {
            throw new \RuntimeException('Unable to decode BER of parameters');
        }
        $components = \WPMailSMTP\Vendor\phpseclib3\File\ASN1::asn1map($decoded[0], \WPMailSMTP\Vendor\phpseclib3\File\ASN1\Maps\DHParameter::MAP);
        if (!\is_array($components)) {
            throw new \RuntimeException('Unable to perform ASN1 mapping on parameters');
        }
        $decoded = \WPMailSMTP\Vendor\phpseclib3\File\ASN1::decodeBER($key[$type]);
        switch (\true) {
            case empty($decoded):
            case !\is_array($decoded):
            case !isset($decoded[0]['content']):
            case !$decoded[0]['content'] instanceof \WPMailSMTP\Vendor\phpseclib3\Math\BigInteger:
                throw new \RuntimeException('Unable to decode BER of parameters');
        }
        $components[$type] = $decoded[0]['content'];
        return $components;
    }
    /**
     * Convert a private key to the appropriate format.
     *
     * @access public
     * @param \phpseclib3\Math\BigInteger $prime
     * @param \phpseclib3\Math\BigInteger $base
     * @param \phpseclib3\Math\BigInteger $privateKey
     * @param \phpseclib3\Math\BigInteger $publicKey
     * @param string $password optional
     * @param array $options optional
     * @return string
     */
    public static function savePrivateKey(\WPMailSMTP\Vendor\phpseclib3\Math\BigInteger $prime, \WPMailSMTP\Vendor\phpseclib3\Math\BigInteger $base, \WPMailSMTP\Vendor\phpseclib3\Math\BigInteger $privateKey, \WPMailSMTP\Vendor\phpseclib3\Math\BigInteger $publicKey, $password = '', array $options = [])
    {
        $params = ['prime' => $prime, 'base' => $base];
        $params = \WPMailSMTP\Vendor\phpseclib3\File\ASN1::encodeDER($params, \WPMailSMTP\Vendor\phpseclib3\File\ASN1\Maps\DHParameter::MAP);
        $params = new \WPMailSMTP\Vendor\phpseclib3\File\ASN1\Element($params);
        $key = \WPMailSMTP\Vendor\phpseclib3\File\ASN1::encodeDER($privateKey, ['type' => \WPMailSMTP\Vendor\phpseclib3\File\ASN1::TYPE_INTEGER]);
        return self::wrapPrivateKey($key, [], $params, $password, $options);
    }
    /**
     * Convert a public key to the appropriate format
     *
     * @access public
     * @param \phpseclib3\Math\BigInteger $prime
     * @param \phpseclib3\Math\BigInteger $base
     * @param \phpseclib3\Math\BigInteger $publicKey
     * @param array $options optional
     * @return string
     */
    public static function savePublicKey(\WPMailSMTP\Vendor\phpseclib3\Math\BigInteger $prime, \WPMailSMTP\Vendor\phpseclib3\Math\BigInteger $base, \WPMailSMTP\Vendor\phpseclib3\Math\BigInteger $publicKey, array $options = [])
    {
        $params = ['prime' => $prime, 'base' => $base];
        $params = \WPMailSMTP\Vendor\phpseclib3\File\ASN1::encodeDER($params, \WPMailSMTP\Vendor\phpseclib3\File\ASN1\Maps\DHParameter::MAP);
        $params = new \WPMailSMTP\Vendor\phpseclib3\File\ASN1\Element($params);
        $key = \WPMailSMTP\Vendor\phpseclib3\File\ASN1::encodeDER($publicKey, ['type' => \WPMailSMTP\Vendor\phpseclib3\File\ASN1::TYPE_INTEGER]);
        return self::wrapPublicKey($key, $params);
    }
}
