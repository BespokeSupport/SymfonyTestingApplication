<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class AppKernel
 */
class AppKernel extends Kernel
{
    protected $debug = true;

    public function registerBundles()
    {
	    $bundles = array();
    	$bundles[] = new \Symfony\Bundle\FrameworkBundle\FrameworkBundle();
	    $bundles[] = new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle();

        $files = glob(dirname(__FILE__).'/../../src/*/*Bundle.php');

        foreach ($files as $file) {
            $classes = $this->classes($file);
            if (count($classes) && $classes['namespace'] && $classes['class']) {
                require_once($file);
                $classString = $classes['namespace'] .'\\'. $classes['class'];
                $bundles[] = new $classString;
            }
        }

        return $bundles;
    }


    public function classes($filePath)
    {
        $fp = fopen($filePath, 'r');
        $class = $namespace = $buffer = '';
        $i = 0;
        while (!$class) {
            if (feof($fp)) break;

            $buffer .= fread($fp, 512);
            $tokens = token_get_all($buffer);

            if (strpos($buffer, '{') === false) continue;

            for (;$i<count($tokens);$i++) {
                if ($tokens[$i][0] === T_NAMESPACE) {
                    for ($j=$i+1;$j<count($tokens); $j++) {
                        if ($tokens[$j][0] === T_STRING) {
                            $namespace .= '\\'.$tokens[$j][1];
                        } else if ($tokens[$j] === '{' || $tokens[$j] === ';') {
                            break;
                        }
                    }
                }

                if ($tokens[$i][0] === T_CLASS) {
                    for ($j=$i+1;$j<count($tokens);$j++) {
                        if ($tokens[$j] === '{') {
                            $class = $tokens[$i+2][1];
                        }
                    }
                }
            }
        }
        return [
            'namespace' => $namespace,
            'class' => $class
        ];
    }



    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return __DIR__.'/../tmp/'.$this->getEnvironment();
    }
    /**
     * @return string
     */
    public function getLogDir()
    {
        return __DIR__.'/../tmp/';
    }
}