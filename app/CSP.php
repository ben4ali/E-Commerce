<?php

/**
 * Classe pour la gestion dynamique du Content-Security-Policy, à utiliser dans toutes les pages.
 * Auteur : Antoine Langevin
 * Date : 2024-01-02
 */

use Exceptions\CSPNotInitialized;
class CSP {

    private bool $isInitialized;
    private array $scripts;
    private array $styles;

    public function __construct()
    {
        $this->isInitialized = false;
        $this->scripts = [];
        $this->styles = [];
    }

    public function initialize(): void {
        $this->isInitialized = true;
        $this->scripts = ['self'];
        $this->styles = ['self'];
    }

    public function isInitialized(): bool {
        return $this->isInitialized;
    }

    /**
     * @throws CSPNotInitialized
     */
    public function add($scripts = [], $styles = []): void {
        if (!$this->isInitialized) {
            throw new CSPNotInitialized();
        }

        $this->scripts = array_merge($this->scripts, $scripts);
        $this->styles = array_merge($this->styles, $styles);
    }

    /**
     * @throws CSPNotInitialized
     */
    public function execute(): void {
        if (!$this->isInitialized) {
            throw new CSPNotInitialized();
        }

        $scriptSrc = "script-src " . implode(" ", array_unique($this->scripts)) . ";";
        $styleSrc = "style-src 'self' " . implode(" ", array_unique($this->styles)) . ";";

        $csp = "Content-Security-Policy: default-src 'self'; " . $scriptSrc . $styleSrc . "object-src 'none';";
        //header($csp);

        // Reset after executing
        $this->isInitialized = false;
    }
}