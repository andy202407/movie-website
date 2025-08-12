<?php

class TemplateEngine {
    private $templateDir;
    private $vars = [];
    
    public function __construct($templateDir = 'templates') {
        $this->templateDir = rtrim($templateDir, '/') . '/';
    }
    
    public function assign($key, $value) {
        $this->vars[$key] = $value;
    }
    
    public function assignArray($data) {
        $this->vars = array_merge($this->vars, $data);
    }
    
    public function render($template) {
        $templateFile = $this->templateDir . $template . '.php';
        
        if (!file_exists($templateFile)) {
            throw new Exception("模板文件不存在: $templateFile");
        }
        
        // 将变量提取到当前作用域
        extract($this->vars);
        
        // 开启输出缓冲
        ob_start();
        include $templateFile;
        $content = ob_get_clean();
        
        return $content;
    }
    
    public function display($template) {
        echo $this->render($template);
    }
    
    // 模板函数：安全输出
    public function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    // 模板函数：包含子模板
    public function include_template($template, $data = []) {
        $originalVars = $this->vars;
        $this->assignArray($data);
        $result = $this->render($template);
        $this->vars = $originalVars;
        return $result;
    }
}