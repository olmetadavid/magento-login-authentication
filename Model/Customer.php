<?php

/**
 * Extends Customer standard model to change the authentication system.
 *
 * The authenticate method allows to use an attribute which can be chosen in
 * the admin panel and based the customer authentication on it.
 *
 * @category   Clever
 * @package    Clever_LoginAuth
 * @author David OLMETA <dolmeta@clever-age.com>
 */
class Clever_LoginAuth_Model_Customer extends Mage_Customer_Model_Customer
{

    /**
     * Override the authenticate method.
     */
    public function authenticate($login, $password)
    {
        // Get attribute selected for authentication.
        $authAttribute = Mage::getStoreConfig('customer/login/attributes');

        if (!$authAttribute) {

            // Call the parent only.
            parent::authenticate($login, $password);
        }
        else {

            // Get the customer from the login.
            $customer = Mage::getModel('customer/customer')
                ->getCollection()
                ->addAttributeToFilter($authAttribute, $login)
                ->fetchItem();

            // Load the customer if it was found.
            if ($customer) {
                $this->_getResource()->load($this, $customer->getId());
            }

            if ($this->getConfirmation() && $this->isConfirmationRequired()) {
                throw Mage::exception('Mage_Core', Mage::helper('customer')->__('This account is not confirmed.'), self::EXCEPTION_EMAIL_NOT_CONFIRMED
                );
            }
            if (!$this->validatePassword($password)) {
                throw Mage::exception('Mage_Core', Mage::helper('customer')->__('Invalid login or password.'), self::EXCEPTION_INVALID_EMAIL_OR_PASSWORD
                );
            }
            Mage::dispatchEvent('customer_customer_authenticated', array(
                'model' => $this,
                'password' => $password,
            ));
        }

        return true;
    }

}
