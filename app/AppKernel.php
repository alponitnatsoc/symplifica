<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function __construct($environment, $debug)
    {
        date_default_timezone_set('America/Bogota');
        setlocale(LC_ALL, "es_CO");
        parent::__construct($environment, $debug);
    }
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            //FOSUser
            new FOS\UserBundle\FOSUserBundle(),
            //
            // FOSRest
            new FOS\RestBundle\FOSRestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),

            new RocketSeller\TwoPickBundle\RocketSellerTwoPickBundle(),
            //
            //Api Doc
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            //
            //8p
            new EightPoints\Bundle\GuzzleBundle\GuzzleBundle(),
            //
            //Sonata Admin
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            //
            // OAuthBundle
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            //
            //Solr
            new FS\SolrBundle\FSSolrBundle(),
            //
            new AppBundle\AppBundle(),
            //File bundle
            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            new Sonata\MediaBundle\SonataMediaBundle(),
            new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(),
            new Sonata\IntlBundle\SonataIntlBundle(),

            //Doctrine Migration Bundle
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),

            //Integration with Mandrill - MandrillSwiftMailerBundle
            new Accord\MandrillSwiftMailerBundle\AccordMandrillSwiftMailerBundle(),
            //twilio
            new Vresh\TwilioBundle\VreshTwilioBundle(),

            //KnpSnappyBundle create PDF/image form url/html
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
            // Add excel bundle
            new Liuggio\ExcelBundle\LiuggioExcelBundle()

        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
