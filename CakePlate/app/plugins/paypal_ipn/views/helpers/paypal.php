<?php
/** Paypal Helper part of the PayPal IPN plugin.
  *
  * @author Nick Baker
  * @link http://www.webtechnick.com
  * @license MIT
  */
class PaypalHelper extends AppHelper {
  
  var $helpers = array('Html','Form');

  /**
    *  Setup the config based on the paypal_ipn_config in /plugins/paypal_ipn/config/paypal_ipn_config.php
    */
  function __construct(){
    App::import(array('type' => 'File', 'name' => 'PaypalIpn.PaypalIpnConfig', 'file' => 'config'.DS.'paypal_ipn_config.php'));
    $this->config =& new PaypalIpnConfig();
    parent::__construct();  
  }
  
  /**
    *  function button will create a complete form button to Pay Now, Donate, Add to Cart, or Subscribe using the paypal service.
    *  Configuration for the button is in /config/paypal_ip_config.php
    *  
    *  for this to work the option 'item_name' and 'amount' must be set in the array options or default config options.
    *
    *  Example: 
    *     $paypal->button('Pay Now', array('amount' => '12.00', 'item_name' => 'test item'));
    *     $paypal->button('Subscribe', array('type' => 'subscribe', 'amount' => '60.00', 'term' => 'month', 'period' => '2'));
    *     $paypal->button('Donate', array('type' => 'donate', 'amount' => '60.00'));
    *     $paypal->button('Add To Cart', array('type' => 'addtocart', 'amount' => '15.00'));
    *     $paypal->button('Unsubscribe', array('type' => 'unsubscribe'));
    *     $paypal->button('Checkout', array(
    *      'type' => 'cart',
    *      'items' => array(
    *         array('item_name' => 'Item 1', 'amount' => '120', 'quantity' => 2, 'item_number' => '1234'),
    *         array('item_name' => 'Item 2', 'amount' => '50'),
    *         array('item_name' => 'Item 3', 'amount' => '80', 'quantity' => 3),
    *       )
    *     ));
    *  Test Example:
    *     $paypal->button('Pay Now', array('test' => true, 'amount' => '12.00', 'item_name' => 'test item'));
    *
    * @access public
    * @param String $title takes the title of the paypal button (default "Pay Now" or "Subscribe" depending on option['type'])
    * @param Array $options takes an options array defaults to (configuration in /config/paypal_ipn_config.php)
    * 
    *   helper_options:  
    *      test: true|false switches default settings in /config/paypal_ipn_config.php between settings and testSettings
    *      type: 'paynow', 'addtocart', 'donate', 'unsubscribe', 'cart', or 'subscribe' (default 'paynow')
    *    
    *    You may pass in api name value pairs to be passed directly to the paypal form link.  Refer to paypal.com for a complete list.
    *    some paypal API examples: 
    *      amount: float value
    *      notify_url: string url
    *      item_name: string name of product.
    *      etc...
    */
  function button($title = null, $options = array()){
    if(is_array($title)){
      $options = $title;
      $title = isset($options['label']) ? $options['label'] : null;
    }    
    $defaults = (isset($options['test']) && $options['test']) ? $this->config->testSettings : $this->config->settings; 
    $options = array_merge($defaults, $options);
    $options['type'] = (isset($options['type'])) ? $options['type'] : "paynow";
    
    switch($options['type']){
      case 'subscribe': //Subscribe
        $options['cmd'] = '_xclick-subscriptions';
        $default_title = 'Subscribe';
        $options['no_note'] = 1;
        $options['no_shipping'] = 1;
        $options['src'] = 1;
        $options['sra'] = 1;
        $options = $this->__subscriptionOptions($options);
        break;
      case 'addtocart': //Add To Cart
        $options['cmd'] = '_cart';
        $options['add'] = '1';
        $default_title = 'Add To Cart';
        break;
      case 'donate': //Doante
        $options['cmd'] = '_donations';
        $default_title = 'Donate';
        break;
      case 'unsubscribe': //Unsubscribe
        $options['cmd'] = '_subscr-find';
        $options['alias'] = $options['business'];
        $default_title = 'Unsubscribe';
        break;
      case 'cart': //upload cart
        $options['cmd'] = '_cart';
        $options['upload'] = 1;
        $default_title = 'Checkout';
        $options = $this->__uploadCartOptions($options);
        break;
      default: //Pay Now
        $options['cmd'] = '_xclick';
        $default_title = 'Pay Now';
        break;
    }
    
    $title = (empty($title)) ? $default_title : $title;
    $retval = "<form action='{$options['server']}/cgi-bin/webscr' method='post'><div>";
    unset($options['server']);
    foreach($options as $name => $value){
       $retval .= $this->__hiddenNameValue($name, $value);
    }
    $retval .= $this->__submitButton($title);
    
    return $retval;
  }
  
  /**
   *  __hiddenNameValue constructs the name value pair in a hidden input html tag
   * @access private
   * @param String name is the name of the hidden html element.
   * @param String value is the value of the hidden html element.
   * @access private
   * @return Html form button and close form
   */
  function __hiddenNameValue($name, $value){
    return "<input type='hidden' name='$name' value='$value' />";
  }
  
  /**
   *  __submitButton constructs the submit button from the provided text
   * @param String text | text is the label of the submit button.  Can use plain text or image url.
   * @access private
   * @return Html form button and close form
   */
  function __submitButton($text){
    return "</div>" . $this->Form->end(array('label' => $text));
  }
  
  /**
    * __subscriptionOptions conversts human readable subscription terms 
    * into paypal terms if need be
    *  @access private
    *  @param array options | human readable options into paypal API options
    *     INT period //paypal api period of term, 2, 3, 1
    *     String term //paypal API term //month, year, day, week
    *     Float amount //paypal API amount to charge for perioud of term.
    *  @return array options 
    */
  function __subscriptionOptions($options = array()){
    //Period... every 1, 2, 3, etc.. Term
    if(isset($options['period'])){
      $options['p3'] = $options['period'];
      unset($options['period']);
    }
    //Mount billed
    if(isset($options['amount'])){
      $options['a3'] = $options['amount'];
      unset($options['amount']);
    }
    //Terms, Month(s), Day(s), Week(s), Year(s)
    if(isset($options['term'])){
      switch($options['term']){
        case 'month': $options['t3'] = 'M'; break;
        case 'year': $options['t3'] = 'Y'; break;
        case 'day': $options['t3'] = 'D'; break;
        case 'week': $options['t3'] = 'W'; break;
        default: $options['t3'] = $options['term'];
      }
      unset($options['term']);
    }
    
    return $options;
  }
  
  /**
    * __uploadCartOptions converts an array of items into paypal friendly name/value pairs
    * @access private
    * @param array of options that will be returned with proper paypal friendly name/value pairs for items
    * @return array options
    */
    function __uploadCartOptions($options = array()){
      if(isset($options['items']) && is_array($options['items'])){
        $count = 1;
        foreach($options['items'] as $item){
          foreach($item as $key => $value){
            $options[$key.'_'.$count] = $value;
          }
          $count++;
        }
        unset($options['items']);
      }
      return $options;
    }
}
?>
