<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento_Install
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\Install\Model;

/**
 * Installer model
 */
class Installer extends \Magento\Object
{
    /**
     * Installer data model used to store data between installation steps
     *
     * @var \Magento\Object
     */
    protected $_dataModel;

    /**
     * DB updated model
     *
     * @var \Magento\Module\UpdaterInterface
     */
    protected $_dbUpdater;

    /**
     * Application chache model
     *
     * @var \Magento\App\CacheInterface
     */
    protected $_cache;

    /**
     * Application config model
     *
     * @var \Magento\App\ReinitableConfigInterface
     */
    protected $_config;

    /**
     * @var \Magento\App\Cache\StateInterface
     */
    protected $_cacheState;

    /**
     * @var \Magento\App\Cache\TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     * Core data
     *
     * @var \Magento\Core\Helper\Data
     */
    protected $_coreData = null;

    /**
     * @var \Magento\Module\Updater\SetupFactory
     */
    protected $_setupFactory;

    /**
     * Install installer pear
     *
     * @var \Magento\Install\Model\Installer\Pear
     */
    protected $_installerPear;

    /**
     * Install installer filesystem
     *
     * @var \Magento\Install\Model\Installer\Filesystem
     */
    protected $_filesystem;

    /**
     * Application
     *
     * @var \Magento\Core\Model\App
     */
    protected $_app;

    /**
     * Application
     *
     * @var \Magento\App\State
     */
    protected $_appState;

    /**
     * Store Manager
     *
     * @var \Magento\Core\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * User user
     *
     * @var \Magento\User\Model\UserFactory
     */
    protected $_userModelFactory;

    /**
     * Installer DB model
     *
     * @var \Magento\Install\Model\Installer\Db
     */
    protected $_installerDb;

    /**
     * Installer DB model
     *
     * @var \Magento\Install\Model\Installer\Config
     */
    protected $_installerConfig;

    /**
     * Install session
     *
     * @var \Magento\Session\Generic
     */
    protected $_session;

    /**
     * @var \Magento\App\Resource
     */
    protected $_resource;

    /**
     * @var \Magento\Encryption\EncryptorInterface
     */
    protected $_encryptor;

    /**
     * @var \Magento\Math\Random
     */
    protected $mathRandom;

    /**
     * Configuration arguments
     *
     * @var \Magento\App\Arguments
     */
    protected $_arguments;

    /**
     * @var \Magento\Module\ModuleListInterface
     */
    protected $moduleList;

    /**
     * @var \Magento\Module\DependencyManagerInterface
     */
    protected $dependencyManager;

    /**
     * @var \Magento\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Stdlib\DateTime\TimezoneInterface
     */
    protected $_localeDate;

    /**
     * @var \Magento\Locale\ResolverInterface
     */
    protected $_localeResolver;

    /**
     * @param \Magento\App\ReinitableConfigInterface $config
     * @param \Magento\Module\UpdaterInterface $dbUpdater
     * @param \Magento\App\CacheInterface $cache
     * @param \Magento\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\App\Cache\StateInterface $cacheState
     * @param \Magento\Module\Updater\SetupFactory $setupFactory
     * @param \Magento\App\Arguments $arguments
     * @param \Magento\Core\Model\App $app
     * @param \Magento\App\State $appState
     * @param \Magento\Core\Model\StoreManagerInterface $storeManager
     * @param \Magento\User\Model\UserFactory $userModelFactory
     * @param Installer\Filesystem $filesystem
     * @param Installer\Pear $installerPear
     * @param Installer\Db $installerDb
     * @param Installer\Config $installerConfig
     * @param \Magento\Session\Generic $session
     * @param \Magento\Encryption\EncryptorInterface $encryptor
     * @param \Magento\Math\Random $mathRandom
     * @param \Magento\App\Resource $resource
     * @param \Magento\Module\ModuleListInterface $moduleList
     * @param \Magento\Module\DependencyManagerInterface $dependencyManager
     * @param \Magento\Message\ManagerInterface $messageManager
     * @param \Magento\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Locale\ResolverInterface $localeResolver
     * @param array $data
     */
    public function __construct(
        \Magento\App\ReinitableConfigInterface $config,
        \Magento\Module\UpdaterInterface $dbUpdater,
        \Magento\App\CacheInterface $cache,
        \Magento\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\App\Cache\StateInterface $cacheState,
        \Magento\Module\Updater\SetupFactory $setupFactory,
        \Magento\App\Arguments $arguments,
        \Magento\Core\Model\App $app,
        \Magento\App\State $appState,
        \Magento\Core\Model\StoreManagerInterface $storeManager,
        \Magento\User\Model\UserFactory $userModelFactory,
        \Magento\Install\Model\Installer\Filesystem $filesystem,
        \Magento\Install\Model\Installer\Pear $installerPear,
        \Magento\Install\Model\Installer\Db $installerDb,
        \Magento\Install\Model\Installer\Config $installerConfig,
        \Magento\Session\Generic $session,
        \Magento\Encryption\EncryptorInterface $encryptor,
        \Magento\Math\Random $mathRandom,
        \Magento\App\Resource $resource,
        \Magento\Module\ModuleListInterface $moduleList,
        \Magento\Module\DependencyManagerInterface $dependencyManager,
        \Magento\Message\ManagerInterface $messageManager,
        \Magento\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Locale\ResolverInterface $localeResolver,
        array $data = array()
    ) {
        $this->_dbUpdater = $dbUpdater;
        $this->_config = $config;
        $this->_cache = $cache;
        $this->_cacheState = $cacheState;
        $this->_cacheTypeList = $cacheTypeList;
        $this->_setupFactory = $setupFactory;
        $this->_encryptor = $encryptor;
        $this->mathRandom = $mathRandom;
        $this->_arguments = $arguments;
        $this->_app = $app;
        $this->_appState = $appState;
        $this->_storeManager = $storeManager;
        $this->_userModelFactory = $userModelFactory;
        $this->_filesystem = $filesystem;
        $this->_installerPear = $installerPear;
        $this->_installerDb = $installerDb;
        $this->_installerConfig = $installerConfig;
        $this->_session = $session;
        $this->_resource = $resource;
        $this->moduleList = $moduleList;
        $this->dependencyManager = $dependencyManager;
        $this->messageManager = $messageManager;
        $this->_localeDate = $localeDate;
        $this->_localeResolver = $localeResolver;
        parent::__construct($data);
    }

    /**
     * Checking install status of application
     *
     * @return bool
     */
    public function isApplicationInstalled()
    {
        return $this->_appState->isInstalled();
    }

    /**
     * Get data model
     *
     * @return \Magento\Object
     */
    public function getDataModel()
    {
        if (null === $this->_dataModel) {
            $this->setDataModel($this->_session);
        }
        return $this->_dataModel;
    }

    /**
     * Set data model to store data between installation steps
     *
     * @param \Magento\Object $model
     * @return $this
     */
    public function setDataModel($model)
    {
        $this->_dataModel = $model;
        return $this;
    }

    /**
     * Check packages (pear) downloads
     *
     * @return boolean
     */
    public function checkDownloads()
    {
        try {
            $this->_installerPear->checkDownloads();
            $result = true;
        } catch (\Exception $e) {
            $result = false;
        }
        $this->setDownloadCheckStatus($result);
        return $result;
    }

    /**
     * Check server settings
     *
     * @return bool
     */
    public function checkServer()
    {
        try {
            $this->checkExtensionsLoaded();
            $this->_filesystem->install();
            $result = true;
        } catch (\Exception $e) {
            $result = false;
        }
        $this->setData('server_check_status', $result);
        return $result;
    }

    /**
     * Retrieve server checking result status
     *
     * @return bool
     */
    public function getServerCheckStatus()
    {
        $status = $this->getData('server_check_status');
        if (is_null($status)) {
            $status = $this->checkServer();
        }
        return $status;
    }

    /**
     * Check all necessary extensions are loaded and available
     *
     * @return void
     * @throws \Exception
     */
    protected function checkExtensionsLoaded()
    {
        try {
            foreach ($this->moduleList->getModules() as $moduleData) {
                $this->dependencyManager->checkModuleDependencies($moduleData);
            }
        } catch (\Exception $exception) {
            $this->messageManager->addError($exception->getMessage());
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * Installation config data
     *
     * @param   array $data
     * @return  $this
     */
    public function installConfig($data)
    {
        $data['db_active'] = true;

        $data = $this->_installerDb->checkDbConnectionData($data);

        $this->_installerConfig->setConfigData($data)->install();

        $this->_arguments->reload();
        $this->_resource->setTablePrefix($data['db_prefix']);

        $this->_config->reinit();

        return $this;
    }

    /**
     * Database installation
     *
     * @return $this
     */
    public function installDb()
    {
        $this->_dbUpdater->updateScheme();
        $data = $this->getDataModel()->getConfigData();

        /**
         * Saving host information into DB
         */
        /** @var $setupModel \Magento\Core\Model\Resource\Setup */
        $setupModel = $this->_setupFactory->create('core_setup', 'Magento_Core');

        if (!empty($data['use_rewrites'])) {
            $setupModel->setConfigData(\Magento\Core\Model\Store::XML_PATH_USE_REWRITES, 1);
        }

        if (!empty($data['enable_charts'])) {
            $setupModel->setConfigData(\Magento\Backend\Block\Dashboard::XML_PATH_ENABLE_CHARTS, 1);
        } else {
            $setupModel->setConfigData(\Magento\Backend\Block\Dashboard::XML_PATH_ENABLE_CHARTS, 0);
        }

        if (!empty($data['admin_no_form_key'])) {
            $setupModel->setConfigData('admin/security/use_form_key', 0);
        }

        $unsecureBaseUrl = $this->_storeManager->getStore()->getBaseUrl('web');
        if (!empty($data['unsecure_base_url'])) {
            $unsecureBaseUrl = $data['unsecure_base_url'];
            $setupModel->setConfigData(\Magento\Core\Model\Store::XML_PATH_UNSECURE_BASE_URL, $unsecureBaseUrl);
        }

        if (!empty($data['use_secure'])) {
            $setupModel->setConfigData(\Magento\Core\Model\Store::XML_PATH_SECURE_IN_FRONTEND, 1);
            $setupModel->setConfigData(\Magento\Core\Model\Store::XML_PATH_SECURE_BASE_URL, $data['secure_base_url']);
            if (!empty($data['use_secure_admin'])) {
                $setupModel->setConfigData(\Magento\Core\Model\Store::XML_PATH_SECURE_IN_ADMINHTML, 1);
            }
        } elseif (!empty($data['unsecure_base_url'])) {
            $setupModel->setConfigData(\Magento\Core\Model\Store::XML_PATH_SECURE_BASE_URL, $unsecureBaseUrl);
        }

        /**
         * Saving locale information into DB
         */
        $locale = $this->getDataModel()->getLocaleData();
        if (!empty($locale['locale'])) {
            $setupModel->setConfigData($this->_localeResolver->getDefaultLocalePath(), $locale['locale']);
        }
        if (!empty($locale['timezone'])) {
            $setupModel->setConfigData($this->_localeDate->getDefaultTimezonePath(), $locale['timezone']);
        }
        if (!empty($locale['currency'])) {
            $setupModel->setConfigData(\Magento\Directory\Model\Currency::XML_PATH_CURRENCY_BASE, $locale['currency']);
            $setupModel->setConfigData(
                \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_DEFAULT,
                $locale['currency']
            );
            $setupModel->setConfigData(
                \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_ALLOW,
                $locale['currency']
            );
        }

        if (!empty($data['order_increment_prefix'])) {
            $this->_setOrderIncrementPrefix($setupModel, $data['order_increment_prefix']);
        }

        return $this;
    }

    /**
     * Set order number prefix
     *
     * @param \Magento\Core\Model\Resource\Setup $setupModel
     * @param string $orderIncrementPrefix
     * @return void
     */
    protected function _setOrderIncrementPrefix(\Magento\Core\Model\Resource\Setup $setupModel, $orderIncrementPrefix)
    {
        $select = $setupModel->getConnection()->select()->from(
            $setupModel->getTable('eav_entity_type'),
            'entity_type_id'
        )->where(
            'entity_type_code=?',
            'order'
        );
        $data = array(
            'entity_type_id' => $setupModel->getConnection()->fetchOne($select),
            'store_id' => '1',
            'increment_prefix' => $orderIncrementPrefix
        );
        $setupModel->getConnection()->insert($setupModel->getTable('eav_entity_store'), $data);
    }

    /**
     * Create an admin user
     *
     * @param array $data
     * @return void
     */
    public function createAdministrator($data)
    {
        // \Magento\User\Model\User belongs to adminhtml area
        $this->_app->loadAreaPart(
            \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
            \Magento\Core\Model\App\Area::PART_CONFIG
        );

        /** @var $user \Magento\User\Model\User */
        $user = $this->_userModelFactory->create();
        $user->loadByUsername($data['username']);
        // setForceNewPassword(true) - run-time flag to force saving of the entered password
        $user->addData($data)->setForceNewPassword(true)->setRoleId(1)->save();
        $this->_refreshConfig();
    }

    /**
     * Install encryption key into the application, generate and return a random one, if no value is specified
     *
     * @param string $key
     * @return $this
     */
    public function installEncryptionKey($key)
    {
        $this->_encryptor->validateKey($key);
        $this->_installerConfig->replaceTmpEncryptKey($key);
        $this->_refreshConfig();
        return $this;
    }

    /**
     * Return a validated encryption key, generating a random one, if no value was initially provided
     *
     * @param string|null $key
     * @return string
     */
    public function getValidEncryptionKey($key = null)
    {
        if (!$key) {
            $key = md5($this->mathRandom->getRandomString(10));
        }
        $this->_encryptor->validateKey($key);
        return $key;
    }

    /**
     * @return $this
     */
    public function finish()
    {
        $this->_setAppInstalled();
        $this->_refreshConfig();

        /* Enable all cache types */
        foreach (array_keys($this->_cacheTypeList->getTypes()) as $cacheTypeCode) {
            $this->_cacheState->setEnabled($cacheTypeCode, true);
        }
        $this->_cacheState->persist();
        return $this;
    }

    /**
     * Store install date and set application into installed state
     *
     * @return void
     */
    protected function _setAppInstalled()
    {
        $dateTime = date('r');
        $this->_installerConfig->replaceTmpInstallDate($dateTime);
        $this->_appState->setInstallDate($dateTime);
    }

    /**
     * Ensure changes in the configuration, if any, take effect
     *
     * @return void
     */
    protected function _refreshConfig()
    {
        $this->_cache->clean();
        $this->_config->reinit();
    }
}
