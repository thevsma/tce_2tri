<?php
class Processar
{
    public function ProcessarInvestimento(Investimento $investimento)
    {
        require_once 'autoloader.class.php';

        R::setup(
            'mysql:host=localhost;dbname=mydatabase',
            'root',
            ''
        );

        R::store($investimento);
    }
}
