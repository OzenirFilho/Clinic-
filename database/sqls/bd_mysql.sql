CREATE DATABASE IF NOT EXISTS `sgg` /*!40100 DEFAULT CHARACTER SET utf8 */;
CREATE DATABASE IF NOT EXISTS `sgs` /*!40100 DEFAULT CHARACTER SET utf8 */;
CREATE DATABASE IF NOT EXISTS `sca` /*!40100 DEFAULT CHARACTER SET utf8 */;

CREATE TABLE IF NOT EXISTS `sgg`.`t0001` (
  `cd_pais` smallint(3) unsigned NOT NULL,
  `nm_pais` varchar(55) NOT NULL,
  PRIMARY KEY (`cd_pais`),
  UNIQUE KEY `UN_SGG_T0001_NM_PAIS` (`nm_pais`) USING BTREE,
  KEY `ID_SGG_T0001_NM_PAIS` (`nm_pais`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='País';

CREATE TABLE IF NOT EXISTS `sgg`.`t0002` (
  `cd_uf` tinyint(2) unsigned NOT NULL,
  `nm_uf` varchar(45) NOT NULL,
  `sg_uf` enum('AC','AL','AM','AP','BA','CE','DF','ES','FN','GO','MA','MG','MS','MT','PA','PB','PE','PI','PR','RJ','RN','RO','RR','RS','SC','SE','SP','XX','TO') NOT NULL COMMENT 'Sigla da Unidade federativa:AC,AL,AM,AP,BA,CE,DF,ES,FN,GO,MA,MG,MS,MT,PA,PB,PE,PI,PR,RJ,RN,RO,RR,RS,SC,SE,SP,XX,TO;',
  `cd_pais` smallint(3) unsigned NOT NULL,
  PRIMARY KEY (`cd_uf`),
  UNIQUE KEY `UN_SGG_T0002_NM_UF` (`nm_uf`) USING BTREE,
  UNIQUE KEY `UN_SGG_T0002_SG_UF` (`sg_uf`) USING BTREE,
  KEY `FK_SGG_T0002_SGG_T0001` (`cd_pais`) USING BTREE,
  KEY `ID_SGG_T0002_NM_UF` (`nm_uf`) USING BTREE,
  KEY `ID_SGG_T0002_SG_UF` (`sg_uf`) USING BTREE,
  CONSTRAINT `FK_SGG_T0002_SGG_T0001` FOREIGN KEY (`cd_pais`) REFERENCES `t0001` (`cd_pais`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Unidade Federativa';

CREATE TABLE IF NOT EXISTS `sgg`.`t0003` (
  `cd_munic` int(6) unsigned NOT NULL,
  `nm_munic` varchar(45) NOT NULL,
  `cd_uf` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`cd_munic`),
  KEY `FK_SGG_T0003_SGG_T0002` (`cd_uf`) USING BTREE,
  KEY `ID_SGG_T0003_NM_MUNIC` (`nm_munic`) USING BTREE,
  CONSTRAINT `FK_SGG_T0003_SGG_T0002` FOREIGN KEY (`cd_uf`) REFERENCES `t0002` (`cd_uf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Município';

CREATE TABLE IF NOT EXISTS `sgs`.`t0001` (
  `cd_especialidade` smallint(4) unsigned NOT NULL,
  `nm_especialidade` varchar(120) NOT NULL,
  `tp_especialidade` enum('E','A') NOT NULL DEFAULT 'E' COMMENT 'E-Especialidade; A-Area de Atuação',
  PRIMARY KEY (`cd_especialidade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Especialidade e Área de Atuação';

CREATE TABLE IF NOT EXISTS `sgs`.`t0002` (
  `cd_cid_inc` int(7) unsigned NOT NULL,
  `cd_cid` char(4) NOT NULL,
  `ds_cid` varchar(255) NOT NULL,
  `tp_sexo` enum('F','I','M') NOT NULL DEFAULT 'I' COMMENT 'F-Feminino, I-Indeferente/Ambos, M-Masculino',
  PRIMARY KEY (`cd_cid_inc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='CID - Codigo Internacional de Doenças';

CREATE TABLE IF NOT EXISTS `sgs`.`t0003` (
  `cd_conselho` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `nm_conselho` varchar(120) CHARACTER SET utf8 DEFAULT NULL,
  `sg_conselho` char(10) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`cd_conselho`),
  UNIQUE KEY `UN_SGS_T0003_NM_CONSELHO` (`nm_conselho`) USING BTREE,
  UNIQUE KEY `UN_SGS_T0003_SG_CONSELHO` (`sg_conselho`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Conselhos';

CREATE TABLE IF NOT EXISTS `sca`.`t0002` (
  `cd_perm_usu` tinyint(2) unsigned AUTO_INCREMENT NOT NULL,
  `nm_perm_usu` varchar(50) NOT NULL,
  `st_perm_usu` enum('0','1') NOT NULL COMMENT '0-Ativo,1-Inativo',
  `nr_tempo_sessao` enum('0','1','2','3') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '0-30 segundos, 1-60 segundos, 2-300 segundos, 3-Nunca expira',
  `tp_expiracao_senha` enum('0','1','2','3','4') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '0-30 dias, 1-45 dias, 2-60 dias, 3-120 dias, 4-Nunca Expira',
  `nr_max_login` enum('0','1','2','3') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '0-3 tentativas, 1-5 tentativas, 2-10 tentativas, 3-Nunca Bloqueia',
  PRIMARY KEY (`cd_perm_usu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Pérfil';

CREATE TABLE IF NOT EXISTS `sca`.`t0001` (
  `cd_usuario` bigint(15) unsigned NOT NULL AUTO_INCREMENT,
  `nr_cpf` bigint(11) unsigned zerofill NOT NULL,
  `nm_usuario` varchar(85) NOT NULL,
  `nm_apelido` varchar(45) NOT NULL,
  `dt_nasc_usu` int(8) unsigned NOT NULL,
  `in_sexo_usu` enum('F','M') NOT NULL,
  `ds_email_usu` varchar(85) NOT NULL,
  `nr_cel_usu` char(13) NOT NULL,
  `cd_perm_usu` tinyint(2) unsigned NOT NULL,
  `ds_snh_usu` varchar(40) NOT NULL,
  `dt_exp_snh_usu` int(8) unsigned NOT NULL,
  `st_usuario` enum('0','1','2') NOT NULL COMMENT '0-Ativo,1-Inativo,2-Primeiro Acesso',
  `ds_snh_usu_ant` varchar(40) NOT NULL,
  `dt_exp_snh_usu_ant` int(8) unsigned NOT NULL,
  `id_sessao` varchar(40) DEFAULT NULL,
  `in_foto` char(11) DEFAULT NULL,
  `in_assinatura` longblob DEFAULT NULL,
  PRIMARY KEY (`cd_usuario`),
  KEY `FK_SCA_T0001_SCA_T0002` (`cd_perm_usu`),
  CONSTRAINT `FK_SCA_T0001_SCA_T0002` FOREIGN KEY (`cd_perm_usu`) REFERENCES `sca`.`t0002` (`cd_perm_usu`),
  UNIQUE KEY `UN_SCA_T0001_NR_CPF` (`nr_cpf`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Usuários do Sistema';

CREATE TABLE IF NOT EXISTS `sgs`.`t0004` (
  `cd_usuario` bigint(15) unsigned NOT NULL,
  `cd_conselho` tinyint(2) unsigned NOT NULL,
  `cd_uf_conselho` tinyint(2) unsigned NOT NULL,
  `nr_conselho` int(7) unsigned NOT NULL,
  PRIMARY KEY (`cd_usuario`,`cd_conselho`,`cd_uf_conselho`),
  KEY `FK_SGS_T0003_SGS_T0003` (`cd_conselho`),
  KEY `FK_SGS_T0003_SGG_T0002` (`cd_uf_conselho`),
  CONSTRAINT `FK_SGS_T0003_SGG_T0002` FOREIGN KEY (`cd_uf_conselho`) REFERENCES `sgg`.`t0002` (`cd_uf`),
  CONSTRAINT `FK_SGS_T0003_SCA_T0001` FOREIGN KEY (`cd_usuario`) REFERENCES `sca`.`t0001` (`cd_usuario`),
  CONSTRAINT `FK_SGS_T0003_SGS_T0003` FOREIGN KEY (`cd_conselho`) REFERENCES `t0003` (`cd_conselho`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Conselho Regional do Profissional em seu Respectivo UF';

CREATE TABLE IF NOT EXISTS `sca`.`t0003` (
  `cd_sistema` tinyint(2) unsigned NOT NULL,
  `nm_sistema` varchar(85) NOT NULL,
  `st_sistema` enum('0','1','2') NOT NULL COMMENT '0-Ativo,1-Inativo',
  `sg_sistema` char(3) NOT NULL,
  PRIMARY KEY (`cd_sistema`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Sistema';

CREATE TABLE IF NOT EXISTS `sca`.`t0004` (
  `cd_sistema` tinyint(2) unsigned NOT NULL,
  `cd_funcao` smallint(4) unsigned NOT NULL,
  `nm_funcao` varchar(50) NOT NULL,
  `nm_controller` char(5) DEFAULT NULL,
  `cd_funcao_hier` smallint(4) unsigned DEFAULT NULL,
  `tp_funcao` enum('0','1','2') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '0-Superior, 1-Modulo, 2-Controler',
  `in_hier_funcao` varchar(20) DEFAULT NULL,
  `st_funcao` enum('0','1') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '0-Ativo, 1-Inativo',
  PRIMARY KEY (`cd_sistema`,`cd_funcao`),
  KEY `FK_SCA_T0007_SCA_T0007` (`cd_sistema`,`cd_funcao_hier`),
  KEY `ID_SCA_T0007_NM_FUNCAO` (`nm_funcao`),
  KEY `ID_SCA_T0007_NM_CONTROLER` (`nm_controller`),
  CONSTRAINT `FK_SCA_T0004_SCA_T0003` FOREIGN KEY (`cd_sistema`) REFERENCES `t0003` (`cd_sistema`),
  CONSTRAINT `FK_SCA_T0004_SCA_T0004` FOREIGN KEY (`cd_sistema`, `cd_funcao_hier`) REFERENCES `t0004` (`cd_sistema`, `cd_funcao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Formulario';

CREATE TABLE IF NOT EXISTS `sca`.`t0005` (
  `cd_botao` tinyint(2) unsigned NOT NULL,
  `nm_botao` varchar(20) NOT NULL,
  `nm_id_botao` varchar(20) NOT NULL,
  `st_botao` enum('0','1') NOT NULL COMMENT '0-Ativo,1-Inativo',
  PRIMARY KEY (`cd_botao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Botao';

CREATE TABLE IF NOT EXISTS `sca`.`t0006` (
  `cd_sistema` tinyint(2) unsigned NOT NULL,
  `cd_funcao` smallint(4) unsigned NOT NULL,
  `cd_botao` tinyint(2) unsigned NOT NULL,
  `in_hier_botao` char(2) NOT NULL,
  PRIMARY KEY (`cd_sistema`,`cd_funcao`,`cd_botao`),
  KEY `FK_SCA_T0006_SCA_T0006` (`cd_botao`),
  CONSTRAINT `FK_SCA_T0006_SCA_T0003` FOREIGN KEY (`cd_sistema`) REFERENCES `t0003` (`cd_sistema`),
  CONSTRAINT `FK_SCA_T0006_SCA_T0004` FOREIGN KEY (`cd_sistema`, `cd_funcao`) REFERENCES `t0004` (`cd_sistema`, `cd_funcao`),
  CONSTRAINT `FK_SCA_T0006_SCA_T0005` FOREIGN KEY (`cd_botao`) REFERENCES `t0005` (`cd_botao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Formulario x Botao';

CREATE TABLE IF NOT EXISTS `sca`.`t0007` (
  `cd_sistema` tinyint(2) unsigned NOT NULL,
  `cd_funcao` smallint(4) unsigned NOT NULL,
  `cd_botao` tinyint(2) unsigned NOT NULL,
  `cd_perm_usu` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`cd_sistema`,`cd_funcao`,`cd_botao`,`cd_perm_usu`),
  KEY `FK_SCA_T0007_SCA_T0006` (`cd_botao`),
  KEY `FK_SCA_T0007_SCA_T0002` (`cd_perm_usu`),
  CONSTRAINT `FK_SCA_T0007_SCA_T0002` FOREIGN KEY (`cd_perm_usu`) REFERENCES `t0002` (`cd_perm_usu`),
  CONSTRAINT `FK_SCA_T0007_SCA_T0003` FOREIGN KEY (`cd_sistema`) REFERENCES `t0003` (`cd_sistema`),
  CONSTRAINT `FK_SCA_T0007_SCA_T0004` FOREIGN KEY (`cd_sistema`, `cd_funcao`) REFERENCES `t0004` (`cd_sistema`, `cd_funcao`),
  CONSTRAINT `FK_SCA_T0007_SCA_T0005` FOREIGN KEY (`cd_botao`) REFERENCES `t0005` (`cd_botao`),
  CONSTRAINT `FK_SCA_T0007_SCA_T0006` FOREIGN KEY (`cd_sistema`, `cd_funcao`, `cd_botao`) REFERENCES `t0006` (`cd_sistema`, `cd_funcao`, `cd_botao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Permissão x Botao';

CREATE TABLE IF NOT EXISTS `sca`.`t0008` (
  `cd_contrato` bigint(15) unsigned NOT NULL,
  `nr_cnpj` bigint(15) unsigned zerofill NOT NULL,
  `nm_razao_social` varchar(120) NOT NULL,
  `nm_fantasia` varchar(120) NOT NULL,
  `ds_email` varchar(85) NOT NULL,
  `in_logo_contrato` char(15) DEFAULT NULL,
  `in_user` varchar(40) NOT NULL,
  `st_contrato` enum('0','1') NOT NULL COMMENT '0-Ativo,1-Inativo',
  PRIMARY KEY (`cd_contrato`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contratos';

CREATE TABLE IF NOT EXISTS `sca`.`t0010` (
  `cd_contrato` bigint(15) unsigned NOT NULL,
  `cd_usuario` bigint(15) unsigned NOT NULL,
  PRIMARY KEY (`cd_contrato`,`cd_usuario`),
  KEY `FK_SCA_T0010_SCA_T0008` (`cd_contrato`),
  KEY `FK_SCA_T0010_SCA_T0001` (`cd_usuario`),
  CONSTRAINT `FK_SCA_T0010_SCA_T0008` FOREIGN KEY (`cd_contrato`) REFERENCES `t0008` (`cd_contrato`),
  CONSTRAINT `FK_SCA_T0010_SCA_T0001` FOREIGN KEY (`cd_usuario`) REFERENCES `t0001` (`cd_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contratos x Usuários do Sistema';

CREATE TABLE IF NOT EXISTS `sca`.`t0011` (
  `nm_database` char(3) NOT NULL,
  `nm_tabela` char(5) NOT NULL,
  `nm_coluna` char(20) NOT NULL,
  `nm_variavel` varchar(45) NOT NULL,
  `tp_variavel` enum('0','1','2','3','4') NOT NULL DEFAULT '0' COMMENT '0-Texto, 1-Data, 2-Hora, 3-CPF, 4-Imagem',
  PRIMARY KEY (`nm_database`,`nm_tabela`,`nm_coluna`),
  UNIQUE KEY `UN_SCA_T0011_NM_VARIAVEL` (`nm_variavel`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Variáveis de ambiente';

CREATE TABLE IF NOT EXISTS `sca`.`t0012` (
  `cd_atestado` int(7) unsigned NOT NULL,
  `nm_atestado` varchar(65) NOT NULL,
  `ds_atestado` text NOT NULL,
  `tp_atestado` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '0-Paciente, 1-Acompanhante, 2-Óbito',
  `st_atestado` enum('0','1') NOT NULL COMMENT '0-Ativo,1-Inativo',
  PRIMARY KEY (`cd_atestado`),
  UNIQUE KEY `UN_SCA_T0012_NM_ATESTADO` (`nm_atestado`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Modelos atestados';

CREATE TABLE IF NOT EXISTS `sca`.`t0009` (
  `cd_contrato` bigint(15) unsigned NOT NULL,
  `cd_integracao` int(7) unsigned NOT NULL,
  `nm_integracao` varchar(120) NOT NULL,
  `in_key` varchar(40) NOT NULL,
  `tp_integracao` enum('0','1','2','3') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '0-Json, 1-Webview, 2-XML, 3-HL7',
  `cd_munic` int(6) unsigned NOT NULL,
  `in_logo_integracao` char(15) DEFAULT NULL,
  `st_integracao` enum('0','1') NOT NULL COMMENT '0-Ativo,1-Inativo',
  `cd_atestado_pac` int(7) unsigned NULL,
  `cd_atestado_aco` int(7) unsigned NULL,
  `cd_atestado_obi` int(7) unsigned NULL,
  PRIMARY KEY (`cd_contrato`, `cd_integracao`),
  KEY `FK_SCA_T0009_SCA_T0008` (`cd_contrato`),
  KEY `FK_SCA_T0009_SCA_T0012_PAC` (`cd_atestado_pac`),
  KEY `FK_SCA_T0009_SCA_T0012_ACO` (`cd_atestado_aco`),
  KEY `FK_SCA_T0009_SCA_T0012_OBI` (`cd_atestado_obi`),
  CONSTRAINT `FK_SCA_T0009_SCA_T0008` FOREIGN KEY (`cd_contrato`) REFERENCES `sca`.`t0008` (`cd_contrato`),
  CONSTRAINT `FK_SCA_T0009_SGG_T0003` FOREIGN KEY (`cd_munic`) REFERENCES `sgg`.`t0003` (`cd_munic`),
  CONSTRAINT `FK_SCA_T0009_SCA_T0012_PAC` FOREIGN KEY (`cd_atestado_pac`) REFERENCES `sca`.`t0012` (`cd_atestado`),
  CONSTRAINT `FK_SCA_T0009_SCA_T0012_ACO` FOREIGN KEY (`cd_atestado_aco`) REFERENCES `sca`.`t0012` (`cd_atestado`),
  CONSTRAINT `FK_SCA_T0009_SCA_T0012_OBI` FOREIGN KEY (`cd_atestado_obi`) REFERENCES `sca`.`t0012` (`cd_atestado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Integração';

CREATE TABLE IF NOT EXISTS `sca`.`t0090` (
  `cd_login_erro` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `dt_login_erro` char(8) NOT NULL,
  `hr_login_erro` char(6) NOT NULL,
  `nr_nivel_log` TINYINT(2) NOT NULL COMMENT "Níveis de log. De 1 a 8, sendo 8 o mais severo",
  `nr_ip_login_erro` char(15) DEFAULT NULL,
  `ds_maq_login_erro` varchar(60) DEFAULT NULL,
  `cd_usuario_blq` bigint(15) unsigned DEFAULT NULL,
  `dt_login_liber` char(8) DEFAULT NULL,
  `hr_login_liber` char(6) DEFAULT NULL,
  `cd_usuario_lib` bigint(15) unsigned DEFAULT NULL,
  PRIMARY KEY (`cd_login_erro`),
  KEY `FK_SCA_T0090_SCA_T0001_BLQ` (`cd_usuario_blq`),
  KEY `FK_SCA_T0090_SCA_T0001_LIB` (`cd_usuario_lib`),
  CONSTRAINT `FK_SCA_T0090_SCA_T0001_BLQ` FOREIGN KEY (`cd_usuario_blq`) REFERENCES `t0001` (`cd_usuario`),
  CONSTRAINT `FK_SCA_T0090_SCA_T0001_LIB` FOREIGN KEY (`cd_usuario_lib`) REFERENCES `t0001` (`cd_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Log Tentativas de Login Falha';

CREATE TABLE IF NOT EXISTS `sca`.`t0091` (
  `id_log_acesso` bigint(15) unsigned NOT NULL AUTO_INCREMENT,
  `id_sessao` varchar(40) NOT NULL,
  `nr_nivel_log` TINYINT(2) NOT NULL COMMENT "Níveis de log. De 1 a 8, sendo 8 o mais severo",
  `dt_acesso` char(8) NOT NULL,
  `hr_acesso` char(6) NOT NULL,
  `cd_usuario` bigint(15) unsigned NOT NULL,
  `nm_usuario` varchar(45) NOT NULL,
  `nr_ip_acesso` char(15) DEFAULT NULL,
  `ds_nome_maquina` varchar(60) DEFAULT NULL,
  `ds_browser` varchar(100) NOT NULL,
  `ds_sis_oper` varchar(100) NOT NULL,
  `ds_res_tela` char(10) NOT NULL,
  PRIMARY KEY (`id_log_acesso`),
  KEY `FK_SCA_T0091_SCA_T0001` (`cd_usuario`),
  CONSTRAINT `FK_SCA_T0091_SCA_T0001` FOREIGN KEY (`cd_usuario`) REFERENCES `t0001` (`cd_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Log Acesso';

CREATE TABLE IF NOT EXISTS `sca`.`t0092` (
  `id_sessao` varchar(40) NOT NULL,
  `dt_oper` char(8) NOT NULL,
  `hr_oper` char(10) NOT NULL,
  `tp_oper` enum('0','1','2') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '0-Autenticado; 1-Não Autenticado; 2-Navegabilidade',
  `cd_sistema` tinyint(2) unsigned NOT NULL,
  `nm_sistema` varchar(85) NOT NULL,
  `sg_sistema` char(3) NOT NULL,
  `cd_funcao` smallint(4) unsigned NOT NULL,
  `nm_funcao` varchar(50) NOT NULL,
  `nm_controller` char(5) DEFAULT NULL,
  `cd_botao` tinyint(2) unsigned NULL,
  `nm_botao` varchar(20) NULL,
  `vl_tempo_exec` char(6) DEFAULT NULL,
  `ds_registro` mediumtext NOT NULL,
  PRIMARY KEY (`id_sessao`,`dt_oper`,`hr_oper`),
  KEY `FK_SCA_T0092_SCA_T0091` (`id_sessao`),
  KEY `FK_SCA_T0092_SCA_T0003` (`cd_sistema`),
  KEY `FK_SCA_T0092_SCA_T0004` (`cd_sistema`, `cd_funcao`),
  KEY `FK_SCA_T0092_SCA_T0006` (`cd_botao`),
  CONSTRAINT `FK_SCA_T0092_SCA_T0091` FOREIGN KEY (`id_sessao`) REFERENCES `t0091`(`id_sessao`),
  CONSTRAINT `FK_SCA_T0092_SCA_T0003` FOREIGN KEY (`cd_sistema`) REFERENCES `t0003` (`cd_sistema`),
  CONSTRAINT `FK_SCA_T0092_SCA_T0004` FOREIGN KEY (`cd_sistema`, `cd_funcao`) REFERENCES `t0004` (`cd_sistema`, `cd_funcao`),
  CONSTRAINT `FK_SCA_T0092_SCA_T0006` FOREIGN KEY (`cd_botao`) REFERENCES `t0006` (`cd_botao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Log Navegabilidade/Acao';


GRANT ALL ON sca.* TO 'supera'@'%';
GRANT ALL ON sgg.* TO 'supera'@'%';
GRANT ALL ON sgs.* TO 'supera'@'%';
