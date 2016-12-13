<?php
namespace BsFile\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use BsFile\Library\BsImage;
use BsFile\Model\Mapper\ImageInterface;
use BsFile\Model\Mapper\FileInterface;
use Zend\Http\Response;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

/**
 * Creates and returns a Response object built from a File entity and returns to controller.
 */
class OutputFile extends AbstractPlugin implements ServiceLocatorAwareInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;

    /**
     * Return HTTP request to the controller
     *
     * @param string|FileInterface $file
     * @param string $disposition
     *            accepts : attachment | inline
     *
     */
    public function __invoke($file, $disposition, $width = null)
    {
        if ($disposition !== 'attachment' && $disposition !== 'inline') {
            $disposition = 'attachment';
        }
        $response = new Response();
        // find file in DB
        if (is_string($file)) {

            $mapper = $this->getServiceLocator()
                ->getServiceLocator()
                ->get('bsfile_mapper');
            $repo = $mapper->getRepository('File');
            // The $file given is the id of a File
            $fileEntity = $repo->find($file);
        } elseif ($file instanceof FileInterface) {
            // The file given is already an Entity
            $fileEntity = $file;
        }

        // if no file found in DB or inactive send 404 response
        if (! $fileEntity || ! $fileEntity->getActive()) {
            return $response->setStatusCode(404);
        }

        // resize image if width is given
        if ($width && $fileEntity instanceof ImageInterface) {
            $content = BsImage::resize($fileEntity, $width,null,true,true);
        } else {
            $content = $fileEntity->getFile()->getBytes();
        }

        // prepare headers
        $etag = md5($content);
        $response->setContent($content);
        $response->getHeaders()
            ->clearHeaders()
            ->addHeaderLine('Content-Disposition:' . $disposition . '; filename="' . $fileEntity->getName() . '"')
            ->addHeaderLine('Content-Type', ($fileEntity->getMime() ?: 'application/octet-stream'))
            ->addHeaderLine('Expires', date(DATE_RFC822, strtotime('+1 year')))
            ->addHeaderLine('Etag', $etag)
            ->addHeaderLine('Pragma', 'private');

        return $response;
    }
}
