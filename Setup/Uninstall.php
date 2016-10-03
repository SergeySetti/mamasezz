<?php

namespace Inchoo\SetupTest\Setup;

use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\BlockRepository;
use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * @property BlockFactory blockFactory
 * @property BlockRepository blockRepository
 */
class Uninstall implements UninstallInterface
{

    /**
     * InstallSchema constructor.
     *
     * @param BlockFactory $blockFactory
     * @param BlockRepository $blockRepository
     */
    public function __construct(
        BlockFactory $blockFactory,
        BlockRepository $blockRepository)
    {
        $this->blockFactory = $blockFactory;
        $this->blockRepository = $blockRepository;
    }
    
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->blockRepository->deleteById('homepage_lower_text_block_left');
        $this->blockRepository->deleteById('homepage_lower_text_block_right');

        $setup->endSetup();
    }
}