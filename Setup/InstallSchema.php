<?php


namespace ItDelight\Ms\Setup;

use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\BlockRepository;
use Magento\Cms\Model\PageRepository;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @property BlockFactory blockFactory
 * @property BlockRepository blockRepository
 * @property PageRepository pageRepository
 */
class InstallSchema implements InstallSchemaInterface
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

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        
        $leftBlock = [
            'title' => 'Homepage Lower Text Block Left',
            'identifier' => 'homepage_lower_text_block_left',
            'stores' => [0],
            'is_active' => 1,
            'content' => file_get_contents('porto_home_bottom_custom_left.phtml',  FILE_USE_INCLUDE_PATH),
        ];

        $rightBlock = [
            'title' => 'Homepage Lower Text Block Right',
            'identifier' => 'homepage_lower_text_block_right',
            'stores' => [0],
            'is_active' => 1,
            'content' => file_get_contents('porto_home_bottom_custom_right.phtml', FILE_USE_INCLUDE_PATH),
        ];

        $newLeftBlock = $this->blockFactory->create(['data' => $leftBlock]);
        $this->blockRepository->save($newLeftBlock);

        $newRightBlock = $this->blockFactory->create(['data' => $rightBlock]);
        $this->blockRepository->save($newRightBlock);

        $this->changeHomePageLayout();

        $setup->endSetup();
    }

    private function changeHomePageLayout()
    {
        $newLayout = '<referenceContainer name="page.top">
    <block class="Magento\Cms\Block\Block" name="home_slider">
        <arguments>
            <argument name="block_id" xsi:type="string">porto_homeslider_custom</argument>
        </arguments>
    </block>
</referenceContainer>
<referenceContainer name="page.bottom">
    <block class="Magento\Cms\Block\Block" name="home_bottom_left" after="-">
        <arguments>
            <argument name="block_id" xsi:type="string">homepage_lower_text_block_left</argument>
        </arguments>
    </block>
    <block class="Magento\Cms\Block\Block" name="home_bottom_right" after="home_bottom_left">
        <arguments>
            <argument name="block_id" xsi:type="string">homepage_lower_text_block_right</argument>
        </arguments>
    </block>
</referenceContainer>';
        
        $cmsPage = $this->pageRepository->getById('home_page');
        
        $cmsPage->setLayoutUpdateXml($newLayout);

        $this->pageRepository->save($cmsPage);            
    }
}