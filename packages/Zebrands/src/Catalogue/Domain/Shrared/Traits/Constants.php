<?php 
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Domain\Shrared\Traits;

trait Constants
{
    static $MERCADOPAGO               = 'mercadopago';
    
    #status in billing
    static $ERROR                     = 'error';           #ERROR EN COBRO
    static $MIGRATE                   = 'migrate';         #NO SE COBRA PRIMERA FACTURA
    static $PENDING                   = 'pending';         #POR COBRAR
    static $EXECUTED                  = 'executed';        #SE COBRO Y ESPERA DE RESPUESTA
    static $ACCREDITED                = 'accredited';      #COBRADO
    static $UNSUBSCRIBE               = 'unsubscribe';     #SOLICITUD DE BAJA
    static $TRIALCANCELED             = 'trialcanceled';   #PRUEBA CANCELADA
    static $UNCOLLECTIBLE             = 'uncollectible';   #INCOBRABLE
    static $PENDING_CAPTURE           = 'pending_capture'; #PENDIENTE DE CAPTURE, NO SE USA

    static $CONFIG_OPERATOR           = 'sas-configuration-operator_id:';
    
    static $CONFIG_PAYMENT_GATEWAY    = 'sas-configuration-payment_gateway:';
    
    static $QUEUE_SCAN                = ['type' => 'scan'];
    
    static $CACHE_USER_ID             = 'user-id:';
    static $CACHE_USER_OP_PTID        = 'user-op_id-pt_id:';
    static $CACHE_PRICE               = 'sas-price-op_id:';
    static $CACHE_NEW_USER            = 'new-user-op_id-customer_id:';
    static $CACHE_CUSTOMER            = 'customer-sas-op-pg:';
    static $CACHE_CUSTOMER_CARD       = 'customer-card-sas-op-pg:';
    static $CACHE_COUPON_CODE         = 'sas-coupon-code:';
    static $CACHE_COUPON_ID           = 'sas-coupon-id:';
    static $CACHE_TEMPLATE_SAS_LEGACY = 'sas-template-coupons-sas_legacy_id:';
    static $CACHE_TEMPLATE_ID         = 'sas-template-coupons-id:';
    static $CACHE_BILLING_ID          = 'sas-billing-id:';
    static $CACHE_BILLING_PAYMENT     = 'sas-billing-p_g_id-pa_ref:';
    static $CACHE_SUBSCRIPTION_ID     = 'sas-subscrption-id:';
    static $CACHE_SUBSCRIPTION_OP_PTID= 'sas-subscrption-op_id-pt_id:';
}