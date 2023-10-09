<?php

namespace App\Controller\Admin;

use App\Entity\Artists;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArtistsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Artists::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextareaField::new('content'),
            ImageField::new('illustration')
                ->setUploadDir('public/upload/')
                ->setBasePath('upload/')
                ->setUploadDir('public/upload/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            TextField::new('twitterUrl'),
            TextField::new('deviantArtUrl'),
            TextField::new('pixiv')
        ];
    }
}
