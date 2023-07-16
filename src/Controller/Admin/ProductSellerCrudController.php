<?php

namespace App\Controller\Admin;

use App\Entity\ProductSeller;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductSellerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductSeller::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
