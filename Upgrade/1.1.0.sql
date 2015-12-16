CREATE TABLE `wst_log_user_logins` (
  `loginId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `loginTime` datetime NOT NULL,
  `loginIp` varchar(16) NOT NULL,
  `loginSrc` tinyint(3) unsigned DEFAULT '0' COMMENT '0:�̳�  1:webapp  2:App',
  `loginRemark` varchar(30) DEFAULT NULL COMMENT '��¼��ע��Ϣ',
  PRIMARY KEY (`loginId`),
  KEY `loginTime` (`loginTime`),
  KEY `userId` (`userId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

rename table wst_log_logins to wst_log_staff_logins;
create index staffId on wst_log_staff_logins(staffId);

insert into wst_sys_configs(parentId,fieldName,fieldCode,fieldType,fieldValue,fieldSort) values(1,'SMTP������','mailSmtp','text','smtp.163.com',1);
insert into wst_sys_configs(parentId,fieldName,fieldCode,fieldType,fieldValue,fieldSort) values(1,'SMTP�˿�','mailPort','text','25',2);
insert into wst_sys_configs(parentId,fieldName,fieldCode,fieldType,valueRangeTxt,valueRange,fieldValue,fieldSort) values(1,'�Ƿ���֤SMTP','mailAuth','radio','��||��','1,0','1',3);
insert into wst_sys_configs(parentId,fieldName,fieldCode,fieldType,fieldValue,fieldSort) values(1,'SMTP����������','mailAddress','text','xxxxx@163.com',4);
insert into wst_sys_configs(parentId,fieldName,fieldCode,fieldType,fieldValue,fieldSort) values(1,'SMTP��¼�˺�','mailUserName','text','username',5);
insert into wst_sys_configs(parentId,fieldName,fieldCode,fieldType,fieldValue,fieldSort) values(1,'SMTP��¼����','mailPassword','text','password',6);
insert into wst_sys_configs(parentId,fieldName,fieldCode,fieldType,fieldValue,fieldSort) values(1,'����������','mailSendTitle','text','WSTMall',7);

CREATE TABLE `wst_log_sms` (
  `smsId` int(11) NOT NULL AUTO_INCREMENT,
  `smsSrc` tinyint(4) DEFAULT '0',
  `smsUserId` int(11) DEFAULT '0',
  `smsContent` varchar(255) DEFAULT NULL,
  `smsPhoneNumber` varchar(11) DEFAULT NULL,
  `smsReturnCode` varchar(255) DEFAULT NULL,
  `smsFunc` varchar(50) DEFAULT NULL,
  `createTime` datetime DEFAULT NULL,
  PRIMARY KEY (`smsId`),
  KEY `logSrcType` (`smsSrc`,`smsPhoneNumber`),
  KEY `createTime` (`createTime`),
  KEY `logFunc` (`smsFunc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

insert into wst_sys_configs(parentId,fieldName,fieldCode,fieldType,fieldValue,fieldSort) values(2,'�����˺�','smsKey','text','WSTMall',1);
insert into wst_sys_configs(parentId,fieldName,fieldCode,fieldType,fieldValue,fieldSort) values(2,'��������','smsPass','text','WSTMall',2);
insert into wst_sys_configs(parentId,fieldName,fieldCode,fieldType,fieldValue,fieldSort,fieldTips) values(2,'����ÿ�շ�����','smsLimit','text','20',3,'��������˷Ѷ��ŵ���Ϊ');

alter table wst_shop_configs add primary key(configId);
alter table wst_shop_configs modify configId int auto_increment;
