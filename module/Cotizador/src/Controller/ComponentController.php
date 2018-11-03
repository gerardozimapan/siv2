<?php
namespace Cotizador\Controller;

use Cotizador\Entity\Brand;
use Cotizador\Entity\Classification;
use Cotizador\Entity\Component;
use Cotizador\Entity\Currency;
use Cotizador\Entity\MeasureUnit;
use Cotizador\Entity\Supplier;
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
     * Image manager.
     * @var Cotizador\Service\ImageManager
     */
    private $imageManager;

    /**
     * Datasheet manager.
     * @var Cotizador\Service\DatasheetManager
     */
    private $datasheetManager;

    /**
     * Constructor.
     */
    public function __construct(
        $entityManager, 
        $componentManager, 
        $imageManager, 
        $datasheetManager
    ){
        $this->entityManager    = $entityManager;
        $this->componentManager = $componentManager;
        $this->imageManager     = $imageManager;
        $this->datasheetManager = $datasheetManager;
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
        $form = new ComponentForm($this->entityManager);

        // Get the list of all availables suppliers (sorted by name).
        $suppliers = $this->entityManager->getRepository(Supplier::class)
            ->findBy([], ['name' => 'ASC']);
        
        $supplierList = [];
        foreach ($suppliers as $supplier) {
            $supplierList[$supplier->getId()] = $supplier->getName();
        }

        $form->get('supplier')->setValueOptions($supplierList);

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
            // $data = $this->params()->fromPost();

            // Make certain to merge the files info!
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

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

        $component = $this->entityManager->getRepository(Component::class)
            ->find($id);
        
        if (null == $component) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create component form.
        $form = new ComponentForm($this->entityManager, $component);

        // Get the list of all availables suppliers (sorted by name).
        $suppliers = $this->entityManager->getRepository(Supplier::class)
            ->findBy([], ['name' => 'ASC']);
        
        $supplierList = [];
        foreach ($suppliers as $supplier) {
            $supplierList[$supplier->getId()] = $supplier->getName();
        }

        $form->get('supplier')->setValueOptions($supplierList);

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
            // Make certain to merge the files info!
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            
            // Fill in the form with POST data.
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
                'folio'                     => $component->getFolio(),
                'function'                  => $component->getFunction(),
                'description'               => $component->getDescription(),
                'listCode'                  => $component->getListCode(),
                'purchaseType'              => $component->getPurchaseType(),
                'supplier'                  => $component->getSupplier()->getId(),
                'brand'                     => $component->getBrand()->getId(),
                'classification'            => $component->getClassification(),
                'purchaseUnit'              => $component->getPurchaseUnit(),
                'inventoryUnit'             => $component->getInventoryUnit(),
                'presentation'              => $component->getPresentation(),
                'amountPresentation'        => $component->getAmountPresentation(),
                'unitPricePurchase'         => $component->getUnitPricePurchase(),
                'presentationPurchasePrice' => $component->getPresentationPurchasePrice(),
                'saleUnitPrice'             => $component->getSaleUnitPrice(),
                'saleTotalPrice'            => $component->getSaleTotalPrice(),
                'unitPriceImportPurchase'   => $component->getUnitPriceImportPurchase(),
                'importSalePrice'           => $component->getImportSalePrice(),
                'currency'                  => $component->getCurrency()->getId(),
                'supplierDeliveryTime'      => $component->getSupplierDeliveryTime(),
                'datasheetFile'             => $component->getDatasheetFile(),
                'imageFile'                 => $component->getImageFile(),
                'satCode'                   => $component->getSatCode(),
            ]);
        }

        return new ViewModel([
            'component' => $component,
            'form'      => $form,
        ]);
    }

    /**
     * Export all components to excel.
     */
    public function exportListToExcelAction()
    {
        // $viewModel = new ViewModel();
        // $viewModel->setTerminal(true);

        $response = $this->getResponse();
        $headers = $response->getHeaders();

        // Get file name generated.
        $filepath = $this->componentManager->exportComponentListToExcel();

        $content = file_get_contents($filepath);

        // Redirect output to a client’s web browser (Xlsx)
        $headers->addHeaderLine('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $headers->addHeaderLine('Content-Disposition: attachment;filename="01simple.xlsx"');
        $headers->addHeaderLine('Cache-Control: max-age=0');
        $headers->addHeaderLine('Content-Length', strlen($content));

        $response->setContent($content);

        // Delete file
        unlink($filepath);

        return $response;
    }

    /** 
     * The 'image' action manage the display of images for each component.
     */
    public function imageAction()
    {
        // Get the name from GET variable.
        $fileName = $this->params()->fromQuery('name', '');

        // Check whether the user needs a thumbnail or a full-size image.
        $isThumbnail = (bool)$this->params()->fromQuery('thumbnail', false);

        // Get path to image file.
        $fileName = $this->imageManager->getImagePathByName($fileName);

        if ($isThumbnail) {
            // Resize the image.
            $fileName = $this->imageManager->resizeImage($fileName);
        }

        // Get image file info (size and MIME type).
        $fileInfo = $this->imageManager->getImageFileInfo($fileName);
        if ($fileInfo === false) {
            // Set 404 Not Found status code
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Write HTTP headers.
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine("Content-type: " . $fileInfo['type']);
        $headers->addHeaderLine("Content-length: " . $fileInfo['size']);

        // Write file content.
        $fileContent = $this->imageManager->getImageFileContent($fileName);
        if ($fileContent != false) {
            $response->setContent($fileContent);
        } else {
            // Set 500 Server Error status code.
            $this->getResponse()->setStatusCode(500);
            return;
        }

        if ($isThumbnail) {
            // Remove temporary thumbnail image file.
            unlink($fileName);
        }

        // Return Response to avoid default view rendering.
        return $this->getResponse();
    }

    /**
     * This action delete a image file and return result in json format.
     */
    public function deleteImageAction() 
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

        // Get filename for delete from file system.
        $filename = $component->getImageFile();

        // Delete filename from database.
        $component->setImageFile('');

        // Apply changes to database.
        $this->entityManager->flush();

        // Delete file from file system.
        $this->imageManager->deleteImageFile($filename);

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);

        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine("Content-Type: application/json");

        $response->setContent(\Zend\Json\Json::encode(['result' => true]));
        return $response;
    }

    /**
     * The 'datasheet' action manage the thumbnail and display pdf datasheet.
     */
    public function datasheetAction()
    {
        // Get the name from GET variable.
        $filename = $this->params()->fromQuery('name', '');

        // Check whether the user needs a thumbnail to display.
        $isThumbnail = (bool)$this->params()->fromQuery('thumbnail', false);

        // Get path to datasheet file.
        $filename = $this->datasheetManager->getDatasheetPathByName($filename);

        if ($isThumbnail) {
            $filename = $this->datasheetManager->createThumbnail($filename);
        }

        // Get image file info (size and MIME type).
        $fileinfo = $this->datasheetManager->getDatasheetFileInfo($filename);
        if ($fileinfo === false) {
            // Set 404 Not Found status code
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Write HTTP headers.
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine("Content-type: " . $fileinfo['type']);
        $headers->addHeaderLine("Content-length: " . $fileinfo['size']);

        // Write file content.
        $fileContent = $this->datasheetManager->getDatasheetFileContent($filename);
        if ($fileContent != false) {
            $response->setContent($fileContent);
        } else {
            // Set 500 Server Error status code.
            $this->getResponse()->setStatusCode(500);
            return;
        }

        // Remove temporary thumbnail image file
        if ($isThumbnail) {
            unlink($filename);
        }

        // Return Response to avoid default view rendering.
        return $this->getResponse();

    }

    /**
     * This action delete a datasheet file and return json format to result.
     */
    public function deleteDatasheetAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Find a component with such ID.
        $component = $this->entityManager->getRepository(Component::class)
            ->find($id);
        
        if (null === $component) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Get filename for delete from file system.
        $filename = $component->getDatasheetFile();

        // Delete filename from database.
        $component->setDatasheetFile('');

        // Apply changes to database.
        $this->entityManager->flush();

        // Delete file from file system.
        $this->datasheetManager->deleteDatasheetFile($filename);

        // No render view.
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);

        // Set header for json response.
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine("Content-Type: application/json");

        // Prepare response.
        $response->setContent(\Zend\Json\Json::encode(['result' => true]));
        return $response;
    }
}