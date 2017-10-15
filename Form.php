<?php

namespace razmik\helper;

class Form
{
    public static $inputOptions = ['class' => 'form-control'];
    public static $options = ['class' => 'form-group'];
    public static $labelOptions = ['class' => 'control-label'];
    
    /**
     * Generate input of type 'text'
     * @param  [type] $name    input name
     * @param  [type] $value   Default value of the input element
     * @param  array  $options Other attributes
     * @return mixed          
     */
    public static function textInput($name, $value = null, $options = [])
    {
        $options = array_merge(static::$inputOptions, $options);
        $input = Html::textInput($name, $value, $options);
        return static::render($input, $options);
    }
    
    /**
     * Generate <input type='hidden' />
     * @param  [type] $name    name attribute of the input 
     * @param  [type] $value   Default value of the input
     * @param  array  $options Other attributes
     * @return mixed    
     */
    public static function hiddenInput($name, $value = null, $options = [])
    {
        return Html::hiddenInput($name, $value, $options);
    }
    
    /**
     * method for generating <textarea>
     * @param  [type] $name    <textarea> name attribute
     * @param  [type] $value   content of the textarea
     * @param  array  $options Other attributes
     * @return mixed         
     */
    public static function textArea($name, $value = null, $options = [])
    {
        $options = array_merge(static::$inputOptions, $options);
        $input = Html::textArea($name, $value, $options);
        return static::render($input, $options);
    }
    
    public static function submitButton($content = 'Submit', $options = [])
    {
        return Html::submitButton($content, $options); 
    }
    

    /**
     * [render description]
     * @param  [type] $input   [description]
     * @param  array  $options [description]
     * @return mixed          [description]
     */
    protected static function render($input = null, $options = [])
    {
        $parts[] = Html::beginTag('div', static::$options);
        if ($options['label']) {
            $parts[] = Html::label($options['label'], null, static::$labelOptions);
        }
        $parts[] = $input;
        $parts[] = Html::endTag('div');

        return implode("\n", $parts);
    }
    
    /**
     * open a new form tag <form>
     * @param  string $action  URI/File to handle the submitted form
     * @param  string $method  E.g POST, GET, PUT, PATCH
     * @param  array  $options Other <form> tag attributes
     * @return mixed          [description]
     */
    public static function beginForm($action = '', $method = 'post', $options = [])
    {
        if (!$action) {
            $action = $_SERVER["REQUEST_URI"];
        }
        
        $action = Url::to($action);

        $options['action'] = $action;
        $options['method'] = $method;

        return Html::beginTag('form', $options);
    }
    
    /**
     * place html form closing tag
     * @return mixed 
     */
    public static function endForm()
    {
        return '</form>';
    }
}