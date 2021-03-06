<?php

namespace App\Request\ParamConverter\Library;

use App\Entity\Library\ProjectBookEdition;
use Rebolon\Request\ParamConverter\AbstractConverter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ProjectBookEditionConverter extends AbstractConverter
{
    const NAME = 'editors';

    const RELATED_ENTITY = ProjectBookEdition::class;

    /**
     * @var EditorConverter
     */
    protected $editorConverter;

    /**
     * ProjectBookEditionConverter constructor.
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param EditorConverter $editorConverter
     */
    public function __construct(
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        EditorConverter $editorConverter
    ) {
        parent::__construct($validator, $serializer, $entityManager);

        $this->editorConverter = $editorConverter;
    }

    /**
     * {@inheritdoc}
     * for this kind of json:
     * {
     *   "editors": {
     *     "publicationsDate": "1519664915",
     *     "collection": "A collection or edition name of the publication",
     *     "isbn": '2-87764-257-7'
     *   }
     * }
     */
    public function getEzPropsName(): array
    {
        return ['id', 'publicationDate', 'collection', 'isbn', ];
    }

    /**
     * {@inheritdoc}
     */
    public function getManyRelPropsName():array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     * for this kind of json:
     * {
     *   "editors": {
     *     "editor": { ... }
     *   }
     * }
     */
    public function getOneRelPropsName():array
    {
        // for instance i don't want to allow the creation of a serie with all embeded books, this is not a usual way of working
        // that's why i don't add books here
        return ['editor' => ['converter' => $this->editorConverter, 'registryKey' => 'editor', ], ];
    }

    /**
     * @inheritdoc
     */
    public function initFromRequest($jsonOrArray, $propertyPath)
    {
        self::$propertyPath[] = $propertyPath;

        $json = $this->checkJsonOrArray($jsonOrArray);

        // the API accept editors as one object or as an array of object, so i need to transform at least in one array
        $editors = $json;
        if (is_object($json)) {
            $editors = [$json];
        }

        $entities = [];
        try {
            foreach ($editors as $editor) {
                self::$propertyPath[count(self::$propertyPath)] = '[' . count($entities) . ']';

                $entities[] = $this->buildEntity($editor);

                array_pop(self::$propertyPath);
            }
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $violationList = new ConstraintViolationList();
            $violation = new ConstraintViolation($e->getMessage(), null, [], null, implode('.', self::$propertyPath), null);
            $violationList->add($violation);
            throw new ValidationException($violationList, 'Wrong parameter to create new Editors (generic)', 420, $e);
        } finally {
            array_pop(self::$propertyPath);
        }

        return $entities;
    }
}
