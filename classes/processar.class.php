<?php
class Processar
{
    public function ProcessarInvestimento(Investimento $investimento)
    {
        require_once 'autoloader.class.php';

        R::setup(
            'mysql:host=localhost;dbname=fintech',
            'root',
            ''
        );

        R::store($investimento);
        R::close();
    }
}
