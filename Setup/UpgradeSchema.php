<?php
namespace ItDelight\Ms\Setup;

use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\BlockRepository;
use Magento\Cms\Model\PageRepository;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * @property BlockFactory blockFactory
 * @property BlockRepository blockRepository
 * @property PageRepository pageRepository
 */
class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * InstallSchema constructor.
     *
     * @param BlockFactory $blockFactory
     * @param PageRepository $pageRepository
     * @param BlockRepository $blockRepository
     */
    public function __construct(
        BlockFactory $blockFactory,
        PageRepository $pageRepository,
        BlockRepository $blockRepository)
    {
        $this->blockFactory = $blockFactory;
        $this->blockRepository = $blockRepository;
        $this->pageRepository = $pageRepository;
    }
    
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $this->changeHomePageContent();
        }
        
        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $this->changeHomePageSlider();
        }

        $setup->endSetup();
    }

    public function changeHomePageSlider()
    {
        $newContent = file_get_contents('homslider_content.phtml', FILE_USE_INCLUDE_PATH);
        
        $homeSlider = $this->blockRepository->getById('porto_homeslider_custom');

        $homeSlider->setContent($newContent);

        $this->blockRepository->save($homeSlider);
    }

    public function changeHomePageContent()
    {
        $newContent = file_get_contents('home_page_content.phtml', FILE_USE_INCLUDE_PATH);
        
        $cmsPage = $this->pageRepository->getById('home_page');

        $cmsPage->setContent($newContent);

        $this->pageRepository->save($cmsPage);
    }
}