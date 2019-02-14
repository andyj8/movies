<?php

use EbysSdk\EbysSdk;
use MovieApps\Service;
use MovieApps\Controller;
use MovieApps\Response\Formatter;

return [
    'config.services' => function () {
        return require 'services.php';
    },

    'logger' => function () {
        return \MovieApps\Logging\LoggerFactory::createLogger();
    },

    'dispatcher' => function ($app) {
        return new Controller\Dispatcher($app);
    },
    
    'controller.soap' => function ($app) {
        return new Controller\Protocol\SoapController(
            $app['session.storage'],
            $app['dispatcher'],
            $app['formatter.xml']
        );
    },
    'controller.json' => function ($app) {
        return new Controller\Protocol\JsonRpcController(
            $app['session.storage'],
            $app['dispatcher'],
            $app['formatter.json']
        );
    },

    'formatter.json' => function () {
        return new Formatter\JsonFormatter();
    },
    'formatter.xml' => function () {
        return new Formatter\XmlFormatter();
    },

    'config' => function() {
        return new Anobii\Config\Config(require __DIR__ . '/../config/config.php');
    },
    'sdk' => function ($app) {
        return new EbysSdk(
            $app['config'],
            $app['session.storage']
        );
    },
    'sdk.product' => function ($app) {
        return $app['sdk']->getService(EbysSdk::SERVICE_PRODUCT);
    },
    'sdk.search' => function ($app) {
        return $app['sdk']->getService(EbysSdk::SERVICE_SEARCH);
    },
    'sdk.genre' => function ($app) {
        return $app['sdk']->getService(EbysSdk::SERVICE_GENRE);
    },

    'session.storage' => function () {
        return new \MovieApps\Session\Storage\FileSessionStorage();
    },

    'client.rmh' => function () {
        return new \RmhApiClient\RmhApiClient("dev");
    },
    'client.slapi' => function () {
        return new \MovieApps\Client\RestSlapiClient(getenv('SLAPI_HOST'));
    },

    'rmh.auth' => function ($app) {
        return $app['client.rmh']->getClientEndpoint("Auth");
    },
    'rmh.user' => function ($app) {
        return $app['client.rmh']->getClientEndpoint("AuthUser");
    },
    'rmh.library' => function ($app) {
        return $app['client.rmh']->getClientEndpoint("Library");
    },
    'rmh.wishlist' => function ($app) {
        return $app['client.rmh']->getClientEndpoint("Wishlist");
    },
    'rmh.utility' => function ($app) {
        return $app['client.rmh']->getClientEndpoint("Utility");
    },
    'rmh.titledata' => function ($app) {
        return $app['client.rmh']->getClientEndpoint("TitleData");
    },
    'rmh.browse' => function ($app) {
        return $app['client.rmh']->getClientEndpoint("Browse");
    },
    'rmh.search' => function ($app) {
        return $app['client.rmh']->getClientEndpoint("Search");
    },
    'rmh.download' => function ($app) {
        return $app['client.rmh']->getClientEndpoint("Download");
    },
    
    'rmh.facade.auth' => function ($app) {
        return $app['client.rmh']->getServiceEndpoint("Authentication");
    },

    'service.utility.getEulaText' => function ($app) {
        return new Service\Util\GetEulaText(
            $app['repository.content']
        );
    },
    'service.utility.getTermsOfService' => function ($app) {
        return new Service\Util\GetTermsOfService(
            $app['repository.content']
        );
    },
    'service.utility.getPrivacyPolicy' => function ($app) {
        return new Service\Util\GetPrivacyPolicy(
            $app['repository.content']
        );
    },
    'service.utility.setupDevice' => function ($app) {
        return new Service\Util\SetupDevice(
            $app['client.slapi']
        );
    },
    'service.utility.getDeviceEnv' => function () {
        return new Service\Util\GetDeviceEnv();
    },
    'service.utility.acceptEula' => function ($app) {
        return new Service\Util\AcceptEula(
            $app['client.slapi']
        );
    },
    'service.user.GetUserBasicAccountInfo' => function ($app) {
        return new Service\User\GetUserBasicAccountInfo(
            $app['client.slapi'],
            $app['logger']
        );
    },
    'service.user.loginUser' => function ($app) {
        return new Service\User\LoginUser(
            $app['client.slapi'],
            $app['rmh.facade.auth'],
            $app['session.storage'],
            new \MovieApps\Session\SessionID\RandomIDGenerator(),
            $app['logger']
        );
    },
    'service.user.loginUserExt' => function ($app) {
        return $app['service.user.loginUser'];
    },
    
    'service.user.registerUser' => function ($app) {
        return new Service\User\RegisterUser(
            $app['client.slapi'],
            $app['rmh.facade.auth'],
            $app['service.user.loginUser'],
            $app['logger']
        );
    },
    'service.user.forgotPassword' => function ($app) {
        return new Service\User\ForgotPassword(
            $app['client.slapi'],
            $app['logger']
        );
    },
    'service.user.getParentalControl' => function ($app) {
        return new Service\User\GetParentalControl(
            $app['client.slapi']
        );
    },
    'service.auth.verifyAuthToken' => function ($app) {
        return new Service\Auth\VerifyAuthToken(
            $app['client.slapi'],
            $app['rmh.facade.auth'],
            $app['logger']
        );
    },

    'service.wishlist.getWishlist' => function ($app) {
        return new Service\Wishlist\GetWishlist(
            $app['client.slapi'],
            $app['sdk.product'],
            $app['logger']
        );
    },
    'service.wishlist.addItemToWishList' => function ($app) {
        return new Service\Wishlist\AddItemToWishList(
            $app['client.slapi'],
            $app['logger']
        );
    },
    'service.wishlist.removeItemFromWishList' => function ($app) {
        return new Service\Wishlist\RemoveItemFromWishList(
            $app['client.slapi'],
            $app['logger']
        );
    },
    'service.wishlist.checkTitleAvailableInWishList' => function ($app) {
        return new Service\Wishlist\CheckTitleAvailableInWishList(
            $app['client.slapi'],
            $app['logger']
        );
    },
    'service.wishlist.checkUserWishListForItems' => function ($app) {
        return new Service\Wishlist\CheckUserWishListForItems(
            $app['client.slapi'],
            $app['logger']
        );
    },
    'service.titledata.getFullSummary' => function ($app) {
        return new Service\TitleData\GetFullSummary(
            $app['sdk.product'],
            $app['response.factory.full_summary']
        );
    },
    'service.titledata.getFullSummaryPlural' => function ($app) {
        return new Service\TitleData\GetFullSummaryPlural(
            $app['sdk.product'],
            $app['response.factory.full_summary']
        );
    },
    'service.titledata.getShortSummary' => function ($app) {
        return new Service\TitleData\GetShortSummary(
            
        );
    },

    'service.browse.getBundleListing' => function ($app) {
        return new Service\Browse\GetBundleListing(
            $app['sdk.product']
        );
    },
    'service.browse.getBrowseList' => function ($app) {
        return new Service\Browse\GetBrowseList(
            $app['sdk.product'],
            $app['response.factory.listed_products']
        );
    },
    'service.browse.getNavigation' => function ($app) {
        return new Service\Browse\GetNavigation(
            $app['sdk.genre']
        );
    },

    'service.search.searchTitleSetOptions' => function ($app) {
        return new Service\Search\SearchTitleSetOptions(
            $app['sdk.search'],
            $app['response.factory.listed_products']
        );
    },
    'service.commerce.calcOrderTax' => function ($app) {
        return new Service\Commerce\CalcOrderTax(
            $app['client.slapi'],
            $app['slapi.basket.creator'],
            $app['logger']
        );
    },
    'service.commerce.checkIfTitleInLibrary' => function ($app) {
        return new Service\Commerce\CheckIfTitleInLibrary(
            $app['client.slapi']
        );
    },
    'service.commerce.getBillingInfo' => function ($app) {
        return new Service\Commerce\GetBillingInfo(
            $app['client.slapi']
        );
    },
    'service.commerce.doPurchase' => function ($app) {
        return new Service\Commerce\DoPurchase(
            $app['client.slapi']
        );
    },

    'service.library.getUserLibraryExtByOptions' => function ($app) {
        return new Service\Library\GetUserLibraryExtByOptions(
            $app['client.slapi'],
            $app['sdk.product'],
            $app['response.factory.library_item']
        );
    },
    'service.library.getPurchasedTitle' => function ($app) {
        return new Service\Library\GetPurchasedTitle(
            $app['client.slapi'],
            $app['service.titledata.getFullSummary'],
            $app['response.factory.library_item']
        );
    },
    'service.library.getLastLibraryUpdateTimeUTC' => function ($app) {
        return new Service\Library\GetLastLibraryUpdateTimeUTC(
            $app['client.slapi']
        );
    },

    'service.download.pollForDownloads' => function () {
        return new Service\Download\PollForDownloads();
    },
    
    'repository.content' => function () {
        return new \MovieApps\Repository\FileContentRepository();
    },

    'response.factory.full_summary' => function($app) {
        return new Service\TitleData\Response\FullSummaryPayloadFactory(
            $app['service.wishlist.checkTitleAvailableInWishList']
        );
    },
    'response.factory.library_item' => function () {
        return new Service\Library\Response\LibraryItemPayloadFactory();
    },
    'response.factory.listed_products' => function () {
        return new MovieApps\Response\Generic\ListedProductsFactory();
    },
    
    'slapi.basket.creator' => function ($app) {
        return new Service\Commerce\Shared\SlapiBasketCreator(
            $app['client.slapi']  
        );
    }
];
