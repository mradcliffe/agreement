<?php

namespace Drupal\Tests\agreement\Functional;

/**
 * Tests custom agreement settings.
 *
 * @group agreement
 */
class AgreementCustomUnprivilegedUserTest extends AgreementTestBase {

  /**
   * The user account.
   *
   * @var \Drupal\user\Entity\User
   */
  protected $account;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->account = $this->createUnprivilegedUser();
  }

  /**
   * Asserts that agreement only functions on the front page.
   */
  public function testAgreement() {
    $this->markTestSkipped('@todo figure out why this is broken.');

    $settings = $this->agreement->getSettings();
    $settings['visibility']['settings'] = 1;
    $settings['visibility']['pages'] = ['<front>'];
    $this->agreement->set('settings', $settings);
    $this->agreement->save();

    $this->drupalLogin($this->account);

    // Not sent to agreement page.
    $this->assertNotAgreementPage($this->agreement);

    // Go to front page, open agreement.
    $this->drupalGet('/node');
    $this->assertAgreementPage($this->agreement);

    // Go anywhere else, no agreement.
    $this->drupalGet('/user/' . $this->account->id() . '/edit');
    $this->assertNotAgreementPage($this->agreement);
  }

  /**
   * Asserts the agreement frequency option.
   */
  public function testAgreementFrequency() {
    $this->markTestSkipped('@todo figure out why this is broken.');

    // A) Tests Agreement on every login.
    $settings = $this->agreement->getSettings();
    $settings['frequency'] = 1;
    $this->agreement->set('settings', $settings);
    $this->agreement->save();


    // Log in and open the agreement.
    $this->drupalLogin($this->account);
    $this->assertAgreementPage($this->agreement);
    $this->assertAgreed($this->agreement);

    // Log out, log back in, open agreement.
    $this->drupalLogin($this->account);
    $this->assertAgreementPage($this->agreement);
    $this->assertAgreed($this->agreement);

    // Change password, no agreement.
    $edit = array(
      'current_pass' => $this->account->passRaw,
      'pass[pass1]' => $pass = $this->randomString(),
      'pass[pass2]' => $pass,
    );
    $this->drupalPostForm('/user/' . $this->account->id() . '/edit', $edit, 'Save');

    if ($this->checkForMetaRefresh()) {
      $this->metaRefreshCount = 0;
    }

    $this->assertNotAgreementPage($this->agreement);
    $this->assertSession()
      ->pageTextContains('The changes have been saved.');
  }

  /**
   * Tests the agreement destination functionality.
   *
   * 1. Agreement destination = blank.
   *   - user goes to regular get URL -> redirect to front.
   *   - user goes to node/1 -> redirect to node/1.
   *   - user needs to change password -> redirect to user/%/edit
   * 2. Agreement destination = node/1.
   *   - user goes to regular get URL -> redirect to node/1.
   *   - user goes to user profile -> redirect to node/1.
   *   - user needs to change password -> redirect to user/%/edit.
   */
  public function testAgreementDestination() {
    $this->markTestSkipped('@todo figure out why this is broken.');

    // A) Agreement destination = blank.
    $settings = $this->agreement->getSettings();
    $settings['frequency'] = 0;
    $settings['visibility']['pages'] = [];
    $this->agreement->set('settings', $settings);
    $this->agreement->save();

    // Log in, open agreement, go to front page.
    $this->drupalLogin($this->account);
    $this->drupalGet('/node');
    $this->assertAgreementPage($this->agreement);
    $this->assertAgreed($this->agreement);
    $this->assertFrontPage();

    // Log in, go somewhere other than front page, open agreement, go to user's
    // original destination.
    $this->drupalLogin($this->account);
    $this->drupalGet('/node/' . $this->node->id());
    $this->assertAgreementPage($this->agreement);
    $this->assertAgreed($this->agreement);
    $this->assertSession()
      ->addressMatches('/node\/' . $this->node->id() . '$/');

    // @todo: Log in following password reset link, go somewhere other than
    // front page, open agreement, go to user profile.
    // B) Agreement destination = node/1.
    $settings = $this->agreement->getSettings();
    $settings['destination'] = '/node/1';
    $this->agreement->set('settings', $settings);
    $this->agreement->save();

    // Log in, open agreement, go to node/1.
    $this->drupalLogin($this->account);
    $this->drupalGet('/node');
    $this->assertAgreementPage($this->agreement);
    $this->drupalGet('/user/' . $this->account->id() . '/edit');
    $this->assertAgreementPage($this->agreement);
    $this->assertAgreed($this->agreement);
    $this->assertSession()
      ->addressMatches('/node\/' . $this->node->id() . '$/');

    // Log in, go somewhere other than front page, open agreement, go to node/1.
    $this->drupalLogin($this->account);
    $this->assertAgreementPage($this->agreement);
    $this->drupalGet('/user/' . $this->account->id() . '/edit');
    $this->assertAgreementPage($this->agreement);
    $this->assertAgreed($this->agreement);
    $this->assertSession()
      ->addressMatches('/node\/' . $this->node->id() . '$/');

    // @todo: Log in following password reset link, go somewhere other than
    // front page, open agreement, go to user profile.
  }

}
