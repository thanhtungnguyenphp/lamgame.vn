<?php

namespace App\Services;

class PromptTemplateService
{
    private string $templatePath;

    public function __construct()
    {
        $this->templatePath = base_path('prompt_template');
    }

    public function getJobCreationPrompt(array $inputData): string
    {
        $template = file_get_contents($this->templatePath . '/job_creation.txt');
        
        return str_replace('{input_data}', json_encode($inputData, JSON_PRETTY_PRINT), $template);
    }

    public function loadTemplate(string $templateName, array $variables = []): string
    {
        $filePath = $this->templatePath . '/' . $templateName . '.txt';
        
        if (!file_exists($filePath)) {
            throw new \Exception("Template {$templateName} not found");
        }
        
        $template = file_get_contents($filePath);
        
        foreach ($variables as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }
        
        return $template;
    }
}
