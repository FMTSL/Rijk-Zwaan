<?php
declare(strict_types=1);

namespace VencaX;

use Abraham;
use Exception;
use Nette;

/**
 * Login with Twitter API
 * Class TwitterLogin
 * @package VencaX
 */
class TwitterLogin extends BaseLogin
{
	public const SOCIAL_NAME = 'twitter';

	/** @var Abraham\TwitterOAuth\TwitterOAuth */
	private $twitterOAuth;

	/** @var array Request token */
	private $requestToken;

	/** @var Nette\Http\Session session */
	private $session;


	/**
	 * Twitter
	 * @param $params array - data from config.neon
	 * @param $cookieName String cookie name
	 * @param Nette\Http\Session $session
	 * @param Nette\Http\IResponse $httpResponse
	 * @param Nette\Http\IRequest $httpRequest
	 */
	public function __construct(
		$params,
		$cookieName,
		Nette\Http\Session $session,
		Nette\Http\IResponse $httpResponse,
		Nette\Http\IRequest $httpRequest
	) {
		$this->params = $params;
		$this->cookieName = $cookieName;
		$this->session = $session;
		$this->httpResponse = $httpResponse;
		$this->httpRequest = $httpRequest;
	}


	/**
	 * Get URL for login
	 * @param string $callbackURL
	 * @return string URL login
	 */
	public function getLoginUrl()
	{
		$this->twitterOAuth = new Abraham\TwitterOAuth\TwitterOAuth($this->params['consumerKey'], $this->params['consumerSecret']);
		$this->requestToken = $this->twitterOAuth->oauth(
			'oauth/request_token',
			[
				'oauth_callback' => $this->params['callbackURL'],
			]
		);

		$sessionSection = $this->session->getSection('twitter');
		$sessionSection->oauth_token = $token = $this->requestToken['oauth_token'];
		$sessionSection->oauth_token_secret = $this->requestToken['oauth_token_secret'];

		$loginUrl = $this->twitterOAuth->url(
			'oauth/authorize',
			[
				'oauth_token' => $this->requestToken['oauth_token'],
			]
		);
		return $loginUrl;
	}


	/**
	 * Return info about login user
	 * @param $oauthToken
	 * @param $oauthVerifier
	 * @param false $includeEmail
	 * @return array|object
	 * @throws Abraham\TwitterOAuth\TwitterOAuthException
	 */
	public function getMe($oauthToken, $oauthVerifier, $includeEmail = false)
	{
		$sessionSection = $this->session->getSection('twitter');

		if (($oauthToken != '') && ($sessionSection->oauth_token !== $oauthToken)) {
			//$sessionSection->oauth_status = 'oldtoken';
			throw new Exception('Twitter token is old. Try again login');
		}

		//Create TwitteroAuth object with app key/secret and token key/secret from default phase
		$this->twitterOAuth = new Abraham\TwitterOAuth\TwitterOAuth($this->params['consumerKey'], $this->params['consumerSecret'], $sessionSection->oauth_token, $sessionSection->oauth_token_secret);

		//Request access tokens from twitter
		$access_token = $this->twitterOAuth->oauth(
			'oauth/access_token',
			[
				'oauth_verifier' => $oauthVerifier,
			]
		);

		$this->twitterOAuth = new Abraham\TwitterOAuth\TwitterOAuth($this->params['consumerKey'], $this->params['consumerSecret'], $access_token['oauth_token'], $access_token['oauth_token_secret']);

		if ($includeEmail) {
			$user_info = $this->twitterOAuth->get('account/verify_credentials', ['include_email' => true]);
		} else {
			$user_info = $this->twitterOAuth->get('account/verify_credentials');
		}

		//Save the access tokens
		$sessionSection->access_token = $access_token;

		// Remove no longer needed request tokens
		unset($sessionSection->oauth_token, $sessionSection->oauth_token_secret);

		$this->setSocialLoginCookie(self::SOCIAL_NAME);

		return $user_info;
	}


	/**
	 * Is user last login with this service<
	 * @return bool
	 */
	public function isThisServiceLastLogin()
	{
		if ($this->getSocialLoginCookie() == self::SOCIAL_NAME) {
			return true;
		} else {
			return false;
		}
	}
}
