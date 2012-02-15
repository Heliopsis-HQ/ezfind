<?php
class eZFindCustomClassAttributes
{

    /**
     * Adds custom fields to $doc
     * @param eZSolrDoc $doc
     * @param eZContentObject $object
     * @example $doc->addField( $this->generateFieldName( 'mycustomfield', 'string' ), 'my value' );
     */
    public function setCustomFields( eZSolrDoc $doc, eZContentObject $object )
    {

    }


    const CUSTOM_FIELD_PREFIX = 'attr_';

    /**
     * Field name helper
     * @var ezfSolrDocumentFieldName
     */
    private static $DocumentFieldName;

    /**
     * Generates custom field name
     * @param string $baseName
     * @param string $type
     */
    protected function generateFieldName( $baseName, $type )
    {
        return self::$DocumentFieldName->lookupSchemaName( self::CUSTOM_FIELD_PREFIX . $baseName, $type );
    }

    /**
     * Singletons for child classes
     * key = class name
     * val = instance
     * @var array
     */
    private static $instances = array();

    /**
     * Get singleton instance for class name
     * @param string $className
     * @return eZFindCustomClassAttributes|false
     */
    public static function getInstance( $className )
    {
        if( !self::$DocumentFieldName )
        {
            self::$DocumentFieldName = new ezfSolrDocumentFieldName();
        }

        if( !isset( self::$instances[$className] ) )
        {
            try
            {
                if( !class_exists( $className ) )
                {
                    throw new Exception( 'Could not find ' . $className );
                }

                $instance = new $className();
                if( !is_a($instance, __CLASS__ ) )
                {
                    throw new Exception( $className . ' is not a valid ' . __CLASS__ );
                }

                self::$instances[$className] = $instance;
            }
            catch( Exception $e)
            {
                //fall back to self
                eZDebug::writeWarning( $e->getMessage(), __METHOD__ );
                self::$instances[$className] = false;
            }
        }

        return self::$instances[$className];
    }
}