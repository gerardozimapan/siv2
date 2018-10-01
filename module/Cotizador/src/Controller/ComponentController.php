<?php
namespace Cotizador\Controller;

use Cotizador\Entity\Brand;
use Cotizador\Entity\Classification;
use Cotizador\Entity\Component;
use Cotizador\Entity\Currency;
use Cotizador\Entity\MeasureUnit;
use Cotizador\Form\ComponentForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * This controller is responsible for component management (adding, editing,
 * viewing component).
 */
class ComponentController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Component manager.
     * @var Cotizador\Service\ComponentManager
     */
    private $componentManager;

    /**
     * Constructor.
     */
    public function __construct($entityManager, $componentManager)
    {
        $this->entityManager    = $entityManager;
        $this->componentManager = $componentManager;
    }

    /**
     * This is the default "index" action of the controller. It display the
     * list of components.
     */
    public function indexAction()
    {
        $components = $this->entityManager->getRepository(Component::class)
            ->findBy([], ['id' => 'ASC']);
        
        return new ViewModel([
            'components' => $components,
        ]);
    }

    /**
     * This action display a page allowing to add a new component.
     */
    public function addAction()
    {
        // Create component form.
        $form = new ComponentForm();

        // Get the list of all availables brands (sorted by name).
        $brands = $this->entityManager->getRepository(Brand::class)
            ->findBy([], ['name' => 'ASC']);
        
        $brandList = [];
        foreach ($brands as $brand) {
            $brandList[$brand->getId()] = $brand->getName();
        }

        $form->get('brand')->setValueOptions($brandList);

        // Get the list of all availables classification (sorted by name).
        $classifications = $this->entityManager->getRepository(Classification::class)
            ->findBy([], ['name' => 'ASC']);
        
        $classificationList = [];
        foreach ($classifications as $classification) {
            $classificationList[$classification->getId()] = $classification->getName();
        }

        $form->get('classification')->setValueOptions($classificationList);

        // Get the list of all availables measure units (sorted by name).
        $measureUnits = $this->entityManager->getRepository(MeasureUnit::class)
            ->findBy([], ['name' => 'ASC']);

        $measureUnitList = [];
        foreach ($measureUnits as $mesaureUnit) {
            $measureUnitList[$mesaureUnit->getId()] = $mesaureUnit->getName() . ' (' . $mesaureUnit->getCode() . ')';
        }

        $form->get('purchaseUnit')->setValueOptions($measureUnitList);
        $form->get('inventoryUnit')->setValueOptions($measureUnitList);

        // Get the list of all availables currencies (sorted by name).
        $currencies = $this->entityManager->getRepository(Currency::class)
            ->findBy([], ['name' => 'ASC']);

        $currencyList = [];
        foreach ($currencies as $currency) {
            $currencyList[$currency->getId()] = $currency->getCode();
        }

        $form->get('currency')->setValueOptions($currencyList);

        // Check if user has submitted the form.
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data.
            $data = $this->params()->fromPost();
            $form->setData($data);

            // Validate form.
            if ($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();

                // Add component.
                $component = $this->componentManager->addComponent($data);

                // Redirect to "view" page.
                return $this->redirect()->toRoute(
                    'components',
                    ['action' => 'view', 'id' => $component->getId()]
                );
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * The "view" action display page allowing to view component's details.
     */
    public function viewAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Find a component with such ID.
        $component = $this->entityManager->getRepository(Component::class)
            ->find($id);

        if (null == $component) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'component' => $component,
        ]);
    }

    /**
     * The "edit" action display a page allowing to edit component.
     */
    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $component = $this->entityManager->gerRepository(Component::class)
            ->find($id);
        
        if (null == $component) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create component form.
        $form = new ComponentForm();

        // Check if user has submitted the form.
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data.
            $data = $this->params()->fromRoute();
            $form->setData($data);

            // Validate form.
            if ($form->isValid()) {
                // get filtered and validated data.
                $data = $form->getData();

                // Update the component.
                $this->componentManager->updateComponent($component, $data);

                // Redirect to "view" page.
                return $this->redirect()->toRoute(
                    'components',
                    ['action' => 'view', 'id' => $component->getId()]
                );
            }
        } else {
            $form->setData([
                'folio'                         => $component->getFolio(),
                'function'                      => $component->getFunction(),
                'description'                   => $component->getDescription(),
                'list_code'                     => $component->getListCode(),
                'purchase_type'                 => $component->getPurchaseType(),
                'supplier_id'                   => $component->getSupplier(),
                'brand_id'                      => $component->getBrand(),
                'classification_id'             => $component->getClasification(),
                'purchase_unit'                 => $component->getPurchaseUnit(),
                'inventory_unit'                => $component->getInventoryUnit(),
                'presentation'                  => $component->getPresentation(),
                'amount_presentation'           => $component->getAmountPresentation(),
                'unit_price_purchase'           => $component->getUnitPricePresentation(),
                'presentation_purchase_price'   => $component->getPresentationPurchasePrice(),
                'sale_unit_price'               => $component->getSaleUnitPrice(),
                'sale_total_price'              => $component->getSaleTotalPrice(),
                'unit_price_import_purchase'    => $component->getUnitPriceImportPurchase(),
                'import_sale_price'             => $component->getImportSalePrice(),
                'currency_id'                   => $component->getCurrency(),
                'supplier_delivery_time'        => $component->getSupplierDeliveryTime(),
                'datasheet_file'                => $component->getDatashetFile(),
                'image_file'                    => $component->getImageFile(),
                'sat_code'                      => $component->getSatCode(),
            ]);
        }

        return new ViewModel([
            'component' => $component,
            'form'      => $form,
        ]);
    }
}