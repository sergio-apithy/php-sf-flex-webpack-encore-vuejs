<?php

namespace App\Request\ParamConverter\Library;

use App\Entity\Library\Serie;
use Rebolon\Request\ParamConverter\AbstractConverter;

class SerieConverter extends AbstractConverter
{
    const NAME = 'serie';

    const RELATED_ENTITY = Serie::class;

    /**
     * {@inheritdoc}
     * for this kind of json:
     * {
     *   "serie": {
     *     "name": "The serie name"
     *   }
     * }
     */
    public function getEzPropsName(): array
    {
        return ['id', 'name', ];
    }

    /**
     * {@inheritdoc}
     */
    public function getManyRelPropsName():array
    {
        // for instance i don't want to allow the creation of a serie with all embeded books, this is not a usual way of working
        // that's why i don't add books here
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getOneRelPropsName():array
    {
        return [];
    }
}
