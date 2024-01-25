<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class B2bModuleB2bFormModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $message = null;
        $product_id = Tools::getValue('id_product');
        if($product_id) {
            $product = new Product($product_id);
            $this->context->smarty->assign(array(
                'product_id' => $product_id,
                'product_name' => $product->name[1],
            ));
        }
        // Initialize the 'message' key
        $this->context->smarty->assign('message', '');

        // Handle form submission
        if (Tools::isSubmit('submitB2bForm')) 
        {
            // Retrieve and sanitize form data
            $product_id = (int)Tools::getValue('product_id');
            $customer_name = Tools::getValue('customer_name');
            $contact_number = Tools::getValue('contact_number');
            $location = Tools::getValue('location');

            if (!Validate::isPhoneNumber($contact_number)) {
                
                $this->context->smarty->assign('error_message', 'Error submitting the form.');
                return;
            }
            else {
                // Process form data
                $success = $this->processFormData($product_id, $customer_name, $contact_number, $location);
            }
            if ($success) {
                // Display success message to the user
                $this->context->smarty->assign('message', 'Form submitted successfully!');
            } else {
                // Display error message to the user
                $this->context->smarty->assign('message', 'Error submitting the form.');
            }

        }
        // Render the template
        $this->setTemplate('module:b2bmodule/views/templates/front/b2bform.tpl');
    }

    private function processFormData($product_id, $customer_name, $contact_number, $location)
    {
        // Perform any necessary processing
        // Insert data into the database
        /** @var \Db $db */
        $db = \Db::getInstance();

        $sql = 'INSERT INTO `'._DB_PREFIX_.'b2bmodule` (`product_id`, `customer_name`, `contact_number`, `location`) 
                VALUES ('.(int)$product_id.', "'.$db->escape($customer_name).'", "'.$db->escape($contact_number).'", "'.$db->escape($location).'")';
        $success = $db->execute($sql);

        return $success;
    }
}

