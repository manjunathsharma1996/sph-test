<?php
namespace Drupal\jugaad\Plugin\Block;
//require "./vendor/autoload.php";

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;



/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "qr_code_block",
 *   admin_label = @Translation("QR Code Block"),
 *   category = @Translation("Custom"),
 * )
 */
class QRCodeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = \Drupal::routeMatch()->getParameter('node');
if ($node instanceof \Drupal\node\NodeInterface) {
  // You can get nid and anything else you need from the node object.
  $nid = $node->id();
}
    $nodeLoad = Node::load($nid);
    $link = $nodeLoad->get("product_link")->getValue()[0]['value'];
    $result = Builder::create()
    ->writer(new PngWriter())
    ->writerOptions([])
    ->data($link)
    ->encoding(new Encoding('UTF-8'))
    ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
    ->size(300)
    ->margin(10)
    ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
    //->logoPath(__DIR__.'/symfony.png')
    ->labelText('Scan here')
    ->labelFont(new NotoSans(20))
    ->labelAlignment(new LabelAlignmentCenter())
    ->validateResult(false)
    ->build();
    $dataUri = $result->getDataUri();
    //echo $dataUri;
	return [
		'#theme' => 'qr_code_theme',
    '#data' => $dataUri
	 ];
  }

}
