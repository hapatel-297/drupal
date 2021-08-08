<?php

namespace Drupal\products\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use CodeItNow\BarcodeBundle\Utils\QrCode;
use Drupal\node\Entity\Node;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "product_link_block",
 *   admin_label = @Translation("Product Link Block"),
 * )
 */
class ProductLinkBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {

	/* Fetch product url from current node */
	$link = '';
	$node = \Drupal::routeMatch()->getParameter('node');
	if ($node instanceof \Drupal\node\NodeInterface) {
		$node_id = $node->id();
	}
	if (isset($node_id) && is_numeric($node_id)) {
		$node = Node::load($node_id);
		if(isset($node)) {
			$link = $node->field_product_link->uri;
		}
	}

	/* Generate QR code and return render array */
	$image_markup = "Product link not available";
	if(!empty($link)){
		$qrCode = new QrCode();
		$qrCode
			->setText($link)
			->setSize(300)
			->setPadding(10)
			->setErrorCorrection('high')
			->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
			->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
			->setLabel('Scan here on your mobile')
			->setLabelFontSize(16)
			->setImageType(QrCode::IMAGE_TYPE_PNG)
			;
		$image_markup = '<img src="data:'.$qrCode->getContentType().';base64,'.$qrCode->generate().'" />';
	}
	return [
	  '#markup' => $this->t($image_markup),
	];
  }
  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['product_link_block_settings'] = $form_state->getValue('product_link_block_settings');
  }
}