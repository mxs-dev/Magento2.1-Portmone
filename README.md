# Magento-2.1.6
Модуль Portmone для CMS Magento 2.1.6 

== Description == 
Модуль предназначен для CMS Magento 2.1.x и позволяет использовать Portmone в качестве способа оплат.

Модуль Portmone позволит вашим клиентам быстро и безопасно осуществлять платежи оплаты с карт VISA и Masterсard в вашем магазине. Простые и понятные цены, первоклассный анализ мошенничества и круглосуточная поддержка.

Сайт разработчикa: https://www.portmone.com.ua/r3/uk/
Версия: 1.0.0
Contributor: Portmone Tags: Portmone, open cart, payment, payment gateway, credit card, debit card Requires at least: Open Сart 2.1.0.1 License: Payment Card Industry Data Security Standard (PCI DSS) License URI: https://www.portmone.com.ua/r3/uk/security/

= Start with Portmone = 
Create Free Portmone Account https://www.portmone.com.ua/r3/ecommerce/sign-up

= Allowed currencies = 
С нами ваши клиенты могут совершать покупки в UAH.

== Installation ==

1.Убедитесь в соответствии версий модуля и вашей CMS, они должны совпадать.
2.Зайдите в корневую папку своего сайта.
3.Загрузите папку "app" в корень Вашго сайта. НЕ переживайте, файлы не удалятся, только добавяться новые.

Установка через консоль: 

4.В консоли перейдите в корень сайта и введите следующие команды
	php bin/magento module:enable PortmonePayment_Portmone
	php bin/magento setup:upgrade
	php bin/magento setup:di:compile

Настройка	
5.В админ панели Вашего сайта перейдите во вкладку Store->Configuration->Sales->Payment Methods (Магазин->Конфигурация->Продажи->Методы->оплаты)
6.Заполните все настройки. 
