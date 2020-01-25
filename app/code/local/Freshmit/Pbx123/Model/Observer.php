<?php
error_reporting(E_ALL);
$_SERVER['MAGE_IS_DEVELOPER_MODE'] = true;
$ExternalLibPath = Mage::getModuleDir('', 'Freshmit_Pbx123') . DS . 'lib';
require_once($ExternalLibPath . DS . 'Curl.php');
require_once($ExternalLibPath . DS . 'agilecrm.class.php');

class Freshmit_Pbx123_Model_Observer
{

    public function setAnalyticsOnOrderSuccessPageView(Varien_Event_Observer $observer)
    {
        $orderIds = $observer->getEvent()->getOrderIds();
        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        }
        $collection = Mage::getResourceModel('sales/order_collection')
                ->addFieldToFilter('entity_id', array('in' => $orderIds));
        $orders = array();
        foreach ($collection as $order) {
            $orders[] = $order;
        }

        foreach ($orders as $order) {
            $entity = $order->getData();
            //if($entity['customer_is_guest'] == 0) {   

            /* $order_id = $entity['entity_id'];
              $order = Mage::getModel("sales/order")->load($order_id); */

            $billing_address = $order->getBillingAddress();
            $billAddr = $billing_address->getData();

            if (Mage::getStoreConfig(Freshmit_Pbx123_Helper_Data::XML_PATH_SYNC_CUSTOMERS) == 1) {
                $customer = new AgileCRM_Customer();
                $customer->first_name = $entity['customer_firstname'];
                $customer->last_name = $entity['customer_lastname'];
                $customer->email = $entity['customer_email'];
                $customer->phone = $billAddr['telephone'];
                $customer->company = $billAddr['company'];
                $customer->address = $this->formatBillingAddress($billAddr);

                $agilecrm = new AgileCRM();
                $agilecrm->customerEmail = $entity['customer_email'];
                $agilecrm->hook = AgileCRM::$hooks['customer.created'];
                $agilecrm->payLoad = $customer->getAgileFormat();
                $res = $agilecrm->post();

                if ($res) {
                    Mage::getSingleton('customer/session')->setAgileGuestEmail($entity['customer_email']);
                }
            }

            if (Mage::getStoreConfig(Freshmit_Pbx123_Helper_Data::XML_PATH_SYNC_ORDERS) == 1) {
                $agileOrder = $this->getAgileOrder($order);
                $agilecrm1 = new AgileCRM();
                $agilecrm1->customerEmail = $entity['customer_email'];
                $agilecrm1->hook = AgileCRM::$hooks['order.created'];
                $agilecrm1->payLoad = array("order" => $agileOrder);
                $agilecrm1->post();
            }

            //}
        }
        return $this;
    }
    public function setOnOrderSuccessPageViewafterpay(Varien_Event_Observer $observer)
    {
        $orderIds = $observer->getEvent()->getOrderIds();
        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        }
        $collection = Mage::getResourceModel('sales/order_collection')
                ->addFieldToFilter('entity_id', array('in' => $orderIds));
        $orders = array();
        foreach ($collection as $order) {
            $orders[] = $order;
        }

        foreach ($orders as $order) {
            $entity = $order->getData();
            //if($entity['customer_is_guest'] == 0) {   

            /* $order_id = $entity['entity_id'];
              $order = Mage::getModel("sales/order")->load($order_id); */

            $billing_address = $order->getBillingAddress();
            $billAddr = $billing_address->getData();

            if (Mage::getStoreConfig(Freshmit_Pbx123_Helper_Data::XML_PATH_SYNC_CUSTOMERS) == 1) {
                $customer = new AgileCRM_Customer();
                $customer->first_name = $entity['customer_firstname'];
                $customer->last_name = $entity['customer_lastname'];
                $customer->email = $entity['customer_email'];
                $customer->phone = $billAddr['telephone'];
                $customer->company = $billAddr['company'];
                $customer->address = $this->formatBillingAddress($billAddr);

                $agilecrm = new AgileCRM();
                $agilecrm->customerEmail = $entity['customer_email'];
                $agilecrm->hook = AgileCRM::$hooks['customer.created'];
                $agilecrm->payLoad = $customer->getAgileFormat();
                $res = $agilecrm->post();

                if ($res) {
                    Mage::getSingleton('customer/session')->setAgileGuestEmail($entity['customer_email']);
                }
            }

            if (Mage::getStoreConfig(Freshmit_Pbx123_Helper_Data::XML_PATH_SYNC_ORDERS) == 1) {
                $agileOrder = $this->getAgileOrder($order);
                $agilecrm1 = new AgileCRM();
                $agilecrm1->customerEmail = $entity['customer_email'];
                $agilecrm1->hook = AgileCRM::$hooks['order.created'];
                $agilecrm1->payLoad = array("order" => $agileOrder);
                $agilecrm1->post();
            }

            //}
        }
        return $this;
    }

    public function adminSystemConfigChangedSection()
    {
        if (Mage::getStoreConfig(Freshmit_Pbx123_Helper_Data::XML_PATH_SYNC_OLDCUSTOMERS) == 1) {
        $collection = Mage::getResourceModel('customer/customer_collection')
            ->addNameToSelect()
            ->addAttributeToSelect('email')                
            ->joinAttribute('shipping_prefix', 'customer_address/prefix', 'default_shipping', null, 'left')
            ->joinAttribute('shipping_firstname', 'customer_address/firstname', 'default_shipping', null, 'left')
            ->joinAttribute('shipping_middlename', 'customer_address/middlename', 'default_shipping', null, 'left')
            ->joinAttribute('shipping_lastname', 'customer_address/lastname', 'default_shipping', null, 'left')
            ->joinAttribute('shipping_company', 'customer_address/company', 'default_shipping', null, 'left')
            ->joinAttribute('shipping_street', 'customer_address/street', 'default_shipping', null, 'left')
            ->joinAttribute('shipping_postcode', 'customer_address/postcode', 'default_shipping', null, 'left')
            ->joinAttribute('shipping_city', 'customer_address/city', 'default_shipping', null, 'left')
            ->joinAttribute('shipping_country_id', 'customer_address/country_id', 'default_shipping', null, 'left')
            ->joinAttribute('shipping_region', 'customer_address/region', 'default_shipping', null, 'left')
            ->joinAttribute('shipping_telephone', 'customer_address/telephone', 'default_shipping', null, 'left');
            foreach ($collection as $item)
                {
                $customer = new AgileCRM_Customer();
                $customer->email = $item['email'];
                $customer->first_name = $item['shipping_firstname'];
                $customer->last_name = $item['shipping_lastname'];
                $customer->email= $item['email'];
                $customer->phone = $item['shipping_telephone'];
                $customer->company = $item['shipping_company'];   
                $address = $item['shipping_street'];
                $city = $item['shipping_city'];
                $state = $item['shipping_region'];
                $zip = $item['shipping_postcode'];
                $country = $this->getCountryName($item['shipping_country_id']);
                $customer->address = array("address"=>$address,"city"=> $city,"state"=> $state,"zip" =>$zip,"country"=>$country);
                $agilecrm = new AgileCRM();
                $agilecrm->customerEmail = $item['email'];
                $agilecrm->hook = AgileCRM::$hooks['customer.created'];
                $agilecrm->payLoad = $customer->getAgileFormat();
                 $res = $agilecrm->post();
                $customer->email = $item['email'];
                //loop through his orders
                $orderCollection = Mage::getModel('sales/order')->getCollection();
                $orderCollection->addFieldToFilter('customer_email', $customer->email);
                foreach ($orderCollection as $order)
                {
                $agileOrder = $this->getAgileOrder1($order);
                $agilecrm1 = new AgileCRM();
                $agilecrm1->customerEmail = $customer->email;
                $agilecrm1->hook = AgileCRM::$hooks['order.created'];
                $agilecrm1->payLoad = array("order" => $agileOrder);
                $agilecrm1->post();
                }
                
               }
                
            }
        }

    public function setAnalyticsOnRegisterSuccess(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();  //Fetches the current event
        $customerArr = $event->getCustomer()->getData();
        if (Mage::getStoreConfig(Freshmit_Pbx123_Helper_Data::XML_PATH_SYNC_CUSTOMERS) == 1) {
            $customer = new AgileCRM_Customer();
            $customer->first_name = $customerArr['firstname'];
            $customer->last_name = $customerArr['lastname'];
            $customer->email = $customerArr['email'];

            $agilecrm = new AgileCRM();
            $agilecrm->customerEmail = $customerArr['email'];
            $agilecrm->hook = AgileCRM::$hooks['customer.created'];
            $agilecrm->payLoad = $customer->getAgileFormat();
            $res = $agilecrm->post();

            if ($res) {
                Mage::getSingleton('customer/session')->setAgileGuestEmail($customerArr['email']);
            }
        }
        return $this;
    }
    public function getAgileOrder1($mageOrder)
    {
        $order = new AgileCRM_Order();
        $order->id = $mageOrder->getIncrementId();
        $order->status = $mageOrder->getData('status');
        $order->grandTotal = (float) $mageOrder->getData('base_grand_total');
        $order->note = $mageOrder->getData('customer_note') ? $mageOrder->getData('customer_note') : "";

        $items = $mageOrder->getAllVisibleItems();

        foreach ($items as $item) {
            $product = new AgileCRM_Product();
            $product->id = $item->getProductId();
            $product->name = $item->getName();
            $product->quantity = (int) $item->getQtyOrdered();
            $product->cost = $item->getPrice();
            $product->sku = $item->getSku();
            $order->products[] = $product;
        }

        return $order;
    }
    public function getAgileOrder($mageOrder)
    {
        $order = new AgileCRM_Order();
        $order->id = $mageOrder->getIncrementId();
        $order->status = $mageOrder->getData('status');
        $order->billingAddress = implode(',', (array) $this->formatBillingAddress($mageOrder->getBillingAddress()->getData()));
        $order->shippingAddress = implode(',', (array) $this->formatBillingAddress($mageOrder->getShippingAddress()->getData()));
        $order->grandTotal = (float) $mageOrder->getData('base_grand_total');
        $order->note = $mageOrder->getData('customer_note') ? $mageOrder->getData('customer_note') : "";

        $items = $mageOrder->getAllVisibleItems();

        foreach ($items as $item) {
            $product = new AgileCRM_Product();
            $product->id = $item->getProductId();
            $product->name = $item->getName();
            $product->quantity = (int) $item->getQtyOrdered();
            $product->cost = $item->getPrice();
            $product->sku = $item->getSku();
            $order->products[] = $product;
        }

        return $order;
    }

    private function getCountryName($ccode)
    {
        $countries = array
            (
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua And Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia And Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo',
            'CD' => 'Congo, Democratic Republic',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Cote D\'Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands (Malvinas)',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island & Mcdonald Islands',
            'VA' => 'Holy See (Vatican City State)',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran, Islamic Republic Of',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle Of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KR' => 'Korea',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Lao People\'s Democratic Republic',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libyan Arab Jamahiriya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia, Federated States Of',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'AN' => 'Netherlands Antilles',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territory, Occupied',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'BL' => 'Saint Barthelemy',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts And Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin',
            'PM' => 'Saint Pierre And Miquelon',
            'VC' => 'Saint Vincent And Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome And Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia And Sandwich Isl.',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard And Jan Mayen',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad And Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks And Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States Outlying Islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Viet Nam',
            'VG' => 'Virgin Islands, British',
            'VI' => 'Virgin Islands, U.S.',
            'WF' => 'Wallis And Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        );
        return isset($countries[$ccode]) ? $countries[$ccode] : $ccode;
    }

    private function formatBillingAddress($billAddr)
    {
        $agileAddress = new AgileCRM_Address();
        $agileAddress->address = $billAddr['street'];
        $agileAddress->city = $billAddr['city'];
        $agileAddress->state = $billAddr['region'];
        $agileAddress->zip = $billAddr['postcode'];
        $agileAddress->country = $this->getCountryName($billAddr['country_id']);
        return $agileAddress;
    }

}