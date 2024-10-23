<?php

namespace kivweb\Views;

interface IView {

    public function printOutput(array $templateData, string $pageType): string;

}