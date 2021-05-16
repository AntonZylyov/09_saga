<?php

use BaseMicroservice\Application\Context;
use \BaseMicroservice\Application\HttpMethod;

require __DIR__ . '/../vendor/autoload.php';

$config = \ClientOrchestrator\Config::createFromEnv();
$app = new BaseMicroservice\Application($config);

$app->addRoute(
	HttpMethod::GET,
	'/',
	static function (Context $context): array
	{
		return [
			'result' => 'I am client orchestrator'
		];
	}
);

$app->addRoute(
	HttpMethod::POST,
	'/register_client',
	static function (Context $context): array
	{
		$data = $context->getRequest()->getParsedBody();
		$login = isset($data['login']) ? (string)$data['login'] : '';
		$password = isset($data['password']) ? (string)$data['password'] : '';

		$error = null;
		if ($login === '')
		{
			return [
				'error' => 'Login is empty'
			];
		}
		if ($password === '')
		{
			return [
				'error' => 'Password is too simple'
			];
		}

		$config = $context->getApplication()->getConfig();

		// ���������� ����:
		// ��������� ������
		$clientService = new \ClientOrchestrator\ClientService($config);
		$createClientResult = $clientService->createClient(
			(string)($data['firstName'] ?? ''),
			(string)($data['lastName'] ?? ''),
			(string)($data['email'] ?? ''),
		);
		if (!$createClientResult->isSuccess())
		{
			return [
				'error' => $createClientResult->getError(),
			];
		}

		$clientId = (int)$createClientResult->getData()['id'];
		// ��������� ������������
		$authService = new \ClientOrchestrator\AuthService($config);
		$registerResult = $authService->register(
			$login,
			$password,
			$clientId
		);

		// � ����������� �� ���������� "����������" ���������� �������� ������������,
		// ���� ���� �����������, ���� �������������� (������������)
		if ($registerResult->isSuccess())
		{
			$clientService->approveClient($clientId);
		}
		else
		{
			$clientService->rejectClient($clientId);
			return [
				'error' => $registerResult->getError(),
			];
		}

		return [
			'success' => 'ok'
		];
	}
);

$app->run();
