<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace PrestaShop\PrestaShop\Adapter\Order;

use PrestaShop\PrestaShop\Adapter\Configuration;
use PrestaShop\PrestaShop\Core\Configuration\AbstractMultistoreConfiguration;

/**
 * Gift Settings configuration available in ShopParameters > Order Preferences.
 */
class GiftOptionsConfiguration extends AbstractMultistoreConfiguration
{
    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        return [
            'enable_gift_wrapping' => $this->configuration->getBoolean('PS_GIFT_WRAPPING'),
            'gift_wrapping_price' => $this->configuration->get('PS_GIFT_WRAPPING_PRICE'),
            'gift_wrapping_tax_rules_group' => $this->configuration->get('PS_GIFT_WRAPPING_TAX_RULES_GROUP'),
            'offer_recyclable_pack' => $this->configuration->getBoolean('PS_RECYCLABLE_PACK'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $configuration)
    {
        if ($this->validateConfiguration($configuration)) {
            $shopConstraint = $this->getShopConstraint();

            $this->updateConfigurationValue('PS_GIFT_WRAPPING', 'enable_gift_wrapping', $configuration, $shopConstraint);
            $this->updateConfigurationValue('PS_GIFT_WRAPPING_PRICE', 'gift_wrapping_price', $configuration, $shopConstraint);
            $this->updateConfigurationValue('PS_GIFT_WRAPPING_TAX_RULES_GROUP', 'gift_wrapping_tax_rules_group', $configuration, $shopConstraint);
            $this->updateConfigurationValue('PS_RECYCLABLE_PACK', 'offer_recyclable_pack', $configuration, $shopConstraint);
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function validateConfiguration(array $configuration)
    {
        return isset(
            $configuration['enable_gift_wrapping'],
            $configuration['gift_wrapping_price'],
            $configuration['gift_wrapping_tax_rules_group'],
            $configuration['offer_recyclable_pack']
        );
    }
}
