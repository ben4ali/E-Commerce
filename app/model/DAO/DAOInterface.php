<?php

namespace model\DAO;

interface DAOInterface
{
    /**
     * Créer une nouvelle ligne dans le database.
     *
     */
    public function create($object): bool;

    /**
     * Met à jour un objet dans le database.
     *
     */
    public function update($object): bool;

    /**
     * Supprime un objet du database.
     *
     */
    public function delete($object): bool;

}