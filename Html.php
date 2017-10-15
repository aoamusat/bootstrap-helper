<?php

namespace razmik\helper;

class Html
{
    public static $voidElements = [
        'area' => 1,
        'base' => 1,
        'br' => 1,
        'col' => 1,
        'command' => 1,
        'embed' => 1,
        'hr' => 1,
        'img' => 1,
        'input' => 1,
        'keygen' => 1,
        'link' => 1,
        'meta' => 1,
        'param' => 1,
        'source' => 1,
        'track' => 1,
        'wbr' => 1,
    ];
    
    public static $attributeOrder = [
        'type',
        'id',
        'class',
        'name',
        'value',

        'href',
        'src',
        'action',
        'method',

        'selected',
        'checked',
        'readonly',
        'disabled',
        'multiple',

        'size',
        'maxlength',
        'width',
        'height',
        'rows',
        'cols',

        'alt',
        'title',
        'rel',
        'media',
    ];
  
    public static $dataAttributes = ['data', 'data-ng', 'ng'];
    
    /**
     * [a description]
     * @param  [type] $text    [description]
     * @param  [type] $url     [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public static function a($text, $url = null, $options = [])
    {
        if ($url !== null) {
            $options['href'] = Url::to($url);
        }
        return static::tag('a', $text, $options);
    }
    
    /**
     * [decode description]
     * @param  [type] $content [description]
     * @return [type]          [description]
     */
    public static function decode($content)
    {
        return htmlspecialchars_decode($content, ENT_QUOTES);
    }
    
    /**
     * [encode description]
     * @param  [type]  $content      [description]
     * @param  boolean $doubleEncode [description]
     * @return [type]                [description]
     */
    public static function encode($content, $doubleEncode = true)
    {
        return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $doubleEncode);
    }
    
    /**
     * [tag description]
     * @param  [type] $name    [description]
     * @param  string $content [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public static function tag($name, $content = '', $options = [])
    {
        if ($name === null || $name === false) {
            return $content;
        }
        $html = "<$name" . static::renderTagAttributes($options) . '>';
        return isset(static::$voidElements[strtolower($name)]) ? $html : "$html$content</$name>";
    }
    
    /**
     * [glyph description]
     * @param  [type] $name    [description]
     * @param  array  $options [description]
     * @param  string $tag     [description]
     * @return [type]          [description]
     */
    public static function glyph($name, $options = [], $tag = 'i')
    {
        $class = 'glyphicon glyphicon-' . $name;
        return static::icon($class, $options = [], $tag);
    }
    
    /**
     * [fa description]
     * @param  [type] $name    [description]
     * @param  array  $options [description]
     * @param  string $tag     [description]
     * @return [type]          [description]
     */
    public static function fa($name, $options = [], $tag = 'i')
    {
        $class = 'fa fa-' . $name;
        return static::icon($class, $options = [], $tag);
    }
    
    /**
     * [ion description]
     * @param  [type] $name    [description]
     * @param  array  $options [description]
     * @param  string $tag     [description]
     * @return [type]          [description]
     */
    public static function ion($name, $options = [], $tag = 'i')
    {
        $class = 'icon ion-' . $name;
        return static::icon($class, $options = [], $tag);
    }
    
    /**
     * [icon description]
     * @param  [type] $class   [description]
     * @param  array  $options [description]
     * @param  [type] $tag     [description]
     * @return [type]          [description]
     */
    private static function icon($class, $options = [], $tag)
    {
        return static::tag($tag, null, ['class' => $class]);
    }
    
    /**
     * [beginTag description]
     * @param  [type] $name    [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public static function beginTag($name, $options = [])
    {
        if ($name === null || $name === false) {
            return '';
        }
        return "<$name" . static::renderTagAttributes($options) . '>';
    }
    
    /**
     * [endTag description]
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public static function endTag($name)
    {
        if ($name === null || $name === false) {
            return '';
        }
        return "</$name>";
    }
    
    /**
     * [renderTagAttributes description]
     * @param  [type] $attributes [description]
     * @return [type]             [description]
     */
    public static function renderTagAttributes($attributes)
    {
        if (count($attributes) > 1) {
            $sorted = [];
            foreach (static::$attributeOrder as $name) {
                if (isset($attributes[$name])) {
                    $sorted[$name] = $attributes[$name];
                }
            }
            $attributes = array_merge($sorted, $attributes);
        }

        $html = '';
        foreach ($attributes as $name => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $html .= " $name";
                }
            } elseif (is_array($value)) {
                if (in_array($name, static::$dataAttributes)) {
                    foreach ($value as $n => $v) {
                        if (is_array($v)) {
                            $html .= " $name-$n='" . json_encode($v) . "'";
                        } else {
                            $html .= " $name-$n=\"" . json_encode($v) . '"';
                        }
                    }
                } else {
                    $html .= " $name='" . json_encode($value) . "'";
                }
            } elseif ($value !== null) {
                $html .= " $name=\"" . static::encode($value) . '"';
            }
        }

        return $html;
    }
    
    /**
     * [label description]
     * @param  [type] $content [description]
     * @param  [type] $for     [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public static function label($content, $for = null, $options = [])
    {
        $options['for'] = $for;
        return static::tag('label', $content, $options);
    }
    
    /**
     * [textInput description]
     * @param  [type] $name    [description]
     * @param  [type] $value   [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public static function textInput($name, $value = null, $options = [])
    {
        return static::input('text', $name, $value, $options);
    }
    
    /**
     * [textArea description]
     * @param  [type] $name    [description]
     * @param  string $value   [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public static function textArea($name, $value = '', $options = [])
    {
        $options['name'] = $name;
        return static::tag('textarea', $value, $options);
    }

    /**
     * [input description]
     * @param  [type] $type    [description]
     * @param  [type] $name    [description]
     * @param  [type] $value   [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public static function input($type, $name = null, $value = null, $options = [])
    {
        if (!isset($options['type'])) {
            $options['type'] = $type;
        }
        $options['name'] = $name;
        $options['value'] = $value === null ? null : (string) $value;
        return static::tag('input', '', $options);
    }
    
    /**
     * [submitButton description]
     * @param  string $content [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public static function submitButton($content = 'Submit', $options = [])
    {
        $options['type'] = 'submit';
        return static::button($content, $options);
    }
    
    /**
     * [resetButton description]
     * @param  string $content [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public static function resetButton($content = 'Reset', $options = [])
    {
        $options['type'] = 'reset';
        return static::button($content, $options);
    }
    
    /**
     * [hiddenInput description]
     * @param  [type] $name    [description]
     * @param  [type] $value   [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public static function hiddenInput($name, $value = null, $options = [])
    {
        return static::input('hidden', $name, $value, $options);
    }
    
    /**
     * [passwordInput description]
     * @param  [type] $name    [description]
     * @param  [type] $value   [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public static function passwordInput($name, $value = null, $options = [])
    {
        return static::input('password', $name, $value, $options);
    }
    
    /**
     * [fileInput description]
     * @param  [type] $name    [description]
     * @param  [type] $value   [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public static function fileInput($name, $value = null, $options = [])
    {
        return static::input('file', $name, $value, $options);
    }
    
    /**
     * [button description]
     * @param  string $content [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public static function button($content = 'Button', $options = [])
    {
        if (!isset($options['type'])) {
            $options['type'] = 'button';
        }
        return static::tag('button', $content, $options);
    }
    
    /**
     * [cssFile description]
     * @param  [type] $url     [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public static function cssFile($url, $options = [])
    {
        if (!isset($options['rel'])) {
            $options['rel'] = 'stylesheet';
        }
        if (!isset($options['type'])) {
            $options['type'] = 'text/css';
        }
        $options['href'] = Url::to($url);

        if (isset($options['condition'])) {
            $condition = $options['condition'];
            unset($options['condition']);
            return static::wrapIntoCondition(static::tag('link', '', $options), $condition);
        } elseif (isset($options['noscript']) && $options['noscript'] === true) {
            unset($options['noscript']);
            return '<noscript>' . static::tag('link', '', $options) . '</noscript>';
        } else {
            return static::tag('link', '', $options);
        }
    }
    
    /**
     * [jsFile description]
     * @param  [type] $url     [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public static function jsFile($url, $options = [])
    {
        $options['src'] = Url::to($url);
        if (isset($options['condition'])) {
            $condition = $options['condition'];
            unset($options['condition']);
            return self::wrapIntoCondition(static::tag('script', '', $options), $condition);
        } else {
            return static::tag('script', '', $options);
        }
    }
    
    /**
     * [wrapIntoCondition description]
     * @param  [type] $content   [description]
     * @param  [type] $condition [description]
     * @return [type]            [description]
     */
    private static function wrapIntoCondition($content, $condition)
    {
        if (strpos($condition, '!IE') !== false) {
            return "<!--[if $condition]><!-->\n" . $content . "\n<!--<![endif]-->";
        }
        return "<!--[if $condition]>\n" . $content . "\n<![endif]-->";
    }
    
    /**
     * [mailto description]
     * @param  [type] $text    [description]
     * @param  [type] $email   [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public static function mailto($text, $email = null, $options = [])
    {
        $options['href'] = 'mailto:' . ($email === null ? $text : $email);
        return static::tag('a', $text, $options);
    }
    
    /**
     * [img description]
     * @param  [type] $src     [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public static function img($src, $options = [])
    {
        $options['src'] = Url::to($src);
        if (!isset($options['alt'])) {
            $options['alt'] = '';
        }
        return static::tag('img', '', $options);
    }
    
    /**
     * [alert description]
     * @param  array  $messages [description]
     * @return [type]           [description]
     */
    public static function alert($messages = [])
    {
        $result = null;
        
        if ($messages) {
            foreach ($messages as $key => $el) {
                $result .= static::tag('div', implode('<br/>', $el), ['class' => 'alert alert-' . $key]);
            }
        }
        
        return $result;
    }
}