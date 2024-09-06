SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

INSERT INTO `tl_form` (`tstamp`, `title`, `alias`, `jumpTo`, `sendViaEmail`, `mailerTransport`, `recipient`, `subject`, `format`, `skipEmpty`, `storeValues`, `targetTable`, `customTpl`, `method`, `novalidate`, `attributes`, `formID`, `allowTags`, `fpConfigs`, `fpFill`) VALUES (UNIX_TIMESTAMP(), 'PDF Form Sample', 'pdf-form-sample', 0, 0, '', '', '', 'raw', 0, 0, '', '', 'POST', 0, 'a:2:{i:0;s:0:\"\";i:1;s:0:\"\";}', '', 0, 0x613a313a7b693a323b613a393a7b733a363a2266704e616d65223b733a31303a226d65726765645f706466223b733a31303a22667054656d706c617465223b733a31363a22cf99e4697b7011ee99ff02420ac90302223b733a31343a226670546172676574466f6c646572223b733a31363a22848b1d7d7b6e11ee99ff02420ac90302223b733a31343a2266704e616d6554656d706c617465223b733a32303a226d65726765645f7b7b646174653a3a596d647d7d223b733a31363a226670446f4e6f744f7665727772697465223b733a313a2231223b733a31373a226670496e73657274546167507265666978223b733a303a22223b733a31373a226670496e73657274546167537566666978223b733a303a22223b733a393a226670466c617474656e223b733a313a2231223b733a31313a2266704c65616453746f7265223b733a303a22223b7d7d, '1');
SELECT @pid := LAST_INSERT_ID( );

INSERT INTO `tl_form_field` (`type`, `pid`, `sorting`, `tstamp`, `label`, `name`, `text`, `html`, `options`, `mandatory`, `rgxp`, `placeholder`, `customRgxp`, `errorMsg`, `minlength`, `maxlength`, `maxImageWidth`, `maxImageHeight`, `minval`, `maxval`, `step`, `size`, `multiple`, `mSize`, `extensions`, `storeFile`, `uploadFolder`, `useHomeDir`, `doNotOverwrite`, `class`, `value`, `accesskey`, `fSize`, `customTpl`, `slabel`, `imageSubmit`, `singleSRC`, `invisible`) VALUES ('text', @pid, 128, 1699271682, 'Textfield', 'textfield', NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', 'a:2:{i:0;i:4;i:1;i:40;}', 0, 0, 'jpg,jpeg,gif,png,pdf,doc,docx,xls,xlsx,ppt,pptx', 0, NULL, 0, 0, '', '', '', 0, '', '', 0, NULL, 0);
INSERT INTO `tl_form_field` (`type`, `pid`, `sorting`, `tstamp`, `label`, `name`, `text`, `html`, `options`, `mandatory`, `rgxp`, `placeholder`, `customRgxp`, `errorMsg`, `minlength`, `maxlength`, `maxImageWidth`, `maxImageHeight`, `minval`, `maxval`, `step`, `size`, `multiple`, `mSize`, `extensions`, `storeFile`, `uploadFolder`, `useHomeDir`, `doNotOverwrite`, `class`, `value`, `accesskey`, `fSize`, `customTpl`, `slabel`, `imageSubmit`, `singleSRC`, `invisible`) VALUES ('text', @pid, 64, 1699271682, 'Email', 'email', NULL, NULL, NULL, 0, 'email', '', '', '', 0, 0, 0, 0, '', '', '', 'a:2:{i:0;i:4;i:1;i:40;}', 0, 0, 'jpg,jpeg,gif,png,pdf,doc,docx,xls,xlsx,ppt,pptx', 0, NULL, 0, 0, '', '', '', 0, '', '', 0, NULL, 0);
INSERT INTO `tl_form_field` (`type`, `pid`, `sorting`, `tstamp`, `label`, `name`, `text`, `html`, `options`, `mandatory`, `rgxp`, `placeholder`, `customRgxp`, `errorMsg`, `minlength`, `maxlength`, `maxImageWidth`, `maxImageHeight`, `minval`, `maxval`, `step`, `size`, `multiple`, `mSize`, `extensions`, `storeFile`, `uploadFolder`, `useHomeDir`, `doNotOverwrite`, `class`, `value`, `accesskey`, `fSize`, `customTpl`, `slabel`, `imageSubmit`, `singleSRC`, `invisible`) VALUES ('checkbox', @pid, 384, 1699274051, 'Checkbox menu', 'checkbox', NULL, NULL, 0x613a383a7b693a303b613a333a7b733a353a2276616c7565223b733a303a22223b733a353a226c6162656c223b733a373a224e756d62657273223b733a353a2267726f7570223b733a313a2231223b7d693a313b613a323a7b733a353a2276616c7565223b733a313a2231223b733a353a226c6162656c223b733a373a2256616c75652031223b7d693a323b613a323a7b733a353a2276616c7565223b733a313a2231223b733a353a226c6162656c223b733a373a2256616c75652032223b7d693a333b613a323a7b733a353a2276616c7565223b733a313a2231223b733a353a226c6162656c223b733a373a2256616c75652033223b7d693a343b613a333a7b733a353a2276616c7565223b733a303a22223b733a353a226c6162656c223b733a31303a2243686172616374657273223b733a353a2267726f7570223b733a313a2231223b7d693a353b613a323a7b733a353a2276616c7565223b733a313a2241223b733a353a226c6162656c223b733a373a2256616c75652041223b7d693a363b613a323a7b733a353a2276616c7565223b733a313a2242223b733a353a226c6162656c223b733a373a2256616c75652042223b7d693a373b613a323a7b733a353a2276616c7565223b733a313a2243223b733a353a226c6162656c223b733a373a2256616c75652043223b7d7d, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', 'a:2:{i:0;i:4;i:1;i:40;}', 0, 0, 'jpg,jpeg,gif,png,pdf,doc,docx,xls,xlsx,ppt,pptx', 0, NULL, 0, 0, '', '', '', 0, '', '', 0, NULL, 0);
INSERT INTO `tl_form_field` (`type`, `pid`, `sorting`, `tstamp`, `label`, `name`, `text`, `html`, `options`, `mandatory`, `rgxp`, `placeholder`, `customRgxp`, `errorMsg`, `minlength`, `maxlength`, `maxImageWidth`, `maxImageHeight`, `minval`, `maxval`, `step`, `size`, `multiple`, `mSize`, `extensions`, `storeFile`, `uploadFolder`, `useHomeDir`, `doNotOverwrite`, `class`, `value`, `accesskey`, `fSize`, `customTpl`, `slabel`, `imageSubmit`, `singleSRC`, `invisible`) VALUES ('radio', @pid, 368, 1699271682, 'Radio button', 'radio', NULL, NULL, 0x613a343a7b693a303b613a323a7b733a353a2276616c7565223b733a313a2231223b733a353a226c6162656c223b733a373a2256616c75652031223b7d693a313b613a323a7b733a353a2276616c7565223b733a313a2232223b733a353a226c6162656c223b733a373a2256616c75652032223b7d693a323b613a323a7b733a353a2276616c7565223b733a313a2233223b733a353a226c6162656c223b733a373a2256616c75652033223b7d693a333b613a323a7b733a353a2276616c7565223b733a313a2234223b733a353a226c6162656c223b733a373a2256616c75652034223b7d7d, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', 'a:2:{i:0;i:4;i:1;i:40;}', 0, 0, 'jpg,jpeg,gif,png,pdf,doc,docx,xls,xlsx,ppt,pptx', 0, NULL, 0, 0, '', '', '', 0, '', '', 0, NULL, 0);
INSERT INTO `tl_form_field` (`type`, `pid`, `sorting`, `tstamp`, `label`, `name`, `text`, `html`, `options`, `mandatory`, `rgxp`, `placeholder`, `customRgxp`, `errorMsg`, `minlength`, `maxlength`, `maxImageWidth`, `maxImageHeight`, `minval`, `maxval`, `step`, `size`, `multiple`, `mSize`, `extensions`, `storeFile`, `uploadFolder`, `useHomeDir`, `doNotOverwrite`, `class`, `value`, `accesskey`, `fSize`, `customTpl`, `slabel`, `imageSubmit`, `singleSRC`, `invisible`) VALUES ('submit', @pid, 768, 1699025858, '', '', NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', 'a:2:{i:0;i:4;i:1;i:40;}', 0, 0, 'jpg,jpeg,gif,png,pdf,doc,docx,xls,xlsx,ppt,pptx', 0, NULL, 0, 0, '', '', '', 0, '', 'Daten senden', 0, NULL, 0);
INSERT INTO `tl_form_field` (`type`, `pid`, `sorting`, `tstamp`, `label`, `name`, `text`, `html`, `options`, `mandatory`, `rgxp`, `placeholder`, `customRgxp`, `errorMsg`, `minlength`, `maxlength`, `maxImageWidth`, `maxImageHeight`, `minval`, `maxval`, `step`, `size`, `multiple`, `mSize`, `extensions`, `storeFile`, `uploadFolder`, `useHomeDir`, `doNotOverwrite`, `class`, `value`, `accesskey`, `fSize`, `customTpl`, `slabel`, `imageSubmit`, `singleSRC`, `invisible`) VALUES ('upload', @pid, 704, 1699271682, 'File upload', 'fileupload', NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', 'a:2:{i:0;i:4;i:1;i:40;}', 0, 0, 'jpg,jpeg,gif,png,pdf,doc,docx,xls,xlsx,ppt,pptx', '1', 0xdbec552f7a2111ee9dec02420ac90203, 0, 0, '', '', '', 0, '', '', 0, NULL, 0);
INSERT INTO `tl_form_field` (`type`, `pid`, `sorting`, `tstamp`, `label`, `name`, `text`, `html`, `options`, `mandatory`, `rgxp`, `placeholder`, `customRgxp`, `errorMsg`, `minlength`, `maxlength`, `maxImageWidth`, `maxImageHeight`, `minval`, `maxval`, `step`, `size`, `multiple`, `mSize`, `extensions`, `storeFile`, `uploadFolder`, `useHomeDir`, `doNotOverwrite`, `class`, `value`, `accesskey`, `fSize`, `customTpl`, `slabel`, `imageSubmit`, `singleSRC`, `invisible`) VALUES ('password', @pid, 256, 1699271682, 'Password', 'passwordfield', NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', 'a:2:{i:0;i:4;i:1;i:40;}', 0, 0, 'jpg,jpeg,gif,png,pdf,doc,docx,xls,xlsx,ppt,pptx', 0, NULL, 0, 0, '', '', '', 0, '', '', 0, NULL, 0);
INSERT INTO `tl_form_field` (`type`, `pid`, `sorting`, `tstamp`, `label`, `name`, `text`, `html`, `options`, `mandatory`, `rgxp`, `placeholder`, `customRgxp`, `errorMsg`, `minlength`, `maxlength`, `maxImageWidth`, `maxImageHeight`, `minval`, `maxval`, `step`, `size`, `multiple`, `mSize`, `extensions`, `storeFile`, `uploadFolder`, `useHomeDir`, `doNotOverwrite`, `class`, `value`, `accesskey`, `fSize`, `customTpl`, `slabel`, `imageSubmit`, `singleSRC`, `invisible`) VALUES ('textarea', @pid, 320, 1699271682, 'Textarea', 'textarea', NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', 'a:2:{i:0;s:1:\"4\";i:1;s:2:\"40\";}', 0, 0, 'jpg,jpeg,gif,png,pdf,doc,docx,xls,xlsx,ppt,pptx', 0, NULL, 0, 0, '', '', '', 0, '', '', 0, NULL, 0);
INSERT INTO `tl_form_field` (`type`, `pid`, `sorting`, `tstamp`, `label`, `name`, `text`, `html`, `options`, `mandatory`, `rgxp`, `placeholder`, `customRgxp`, `errorMsg`, `minlength`, `maxlength`, `maxImageWidth`, `maxImageHeight`, `minval`, `maxval`, `step`, `size`, `multiple`, `mSize`, `extensions`, `storeFile`, `uploadFolder`, `useHomeDir`, `doNotOverwrite`, `class`, `value`, `accesskey`, `fSize`, `customTpl`, `slabel`, `imageSubmit`, `singleSRC`, `invisible`) VALUES ('select', @pid, 352, 1699271682, 'Select menu', 'select', NULL, NULL, 0x613a333a7b693a303b613a323a7b733a353a2276616c7565223b733a313a2231223b733a353a226c6162656c223b733a373a2256616c75652031223b7d693a313b613a323a7b733a353a2276616c7565223b733a313a2232223b733a353a226c6162656c223b733a373a2256616c75652032223b7d693a323b613a323a7b733a353a2276616c7565223b733a313a2233223b733a353a226c6162656c223b733a373a2256616c75652033223b7d7d, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', 'a:2:{i:0;i:4;i:1;i:40;}', 0, 0, 'jpg,jpeg,gif,png,pdf,doc,docx,xls,xlsx,ppt,pptx', 0, NULL, 0, 0, '', '', '', 0, '', '', 0, NULL, 0);
INSERT INTO `tl_form_field` (`type`, `pid`, `sorting`, `tstamp`, `label`, `name`, `text`, `html`, `options`, `mandatory`, `rgxp`, `placeholder`, `customRgxp`, `errorMsg`, `minlength`, `maxlength`, `maxImageWidth`, `maxImageHeight`, `minval`, `maxval`, `step`, `size`, `multiple`, `mSize`, `extensions`, `storeFile`, `uploadFolder`, `useHomeDir`, `doNotOverwrite`, `class`, `value`, `accesskey`, `fSize`, `customTpl`, `slabel`, `imageSubmit`, `singleSRC`, `invisible`) VALUES ('range', @pid, 736, 1699271682, 'Range slider', 'rangeslider', NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, 0, '0', '100', '1', 'a:2:{i:0;i:4;i:1;i:40;}', 0, 0, 'jpg,jpeg,gif,png,pdf,doc,docx,xls,xlsx,ppt,pptx', 0, NULL, 0, 0, '', '20', '', 0, '', '', 0, NULL, 0);
INSERT INTO `tl_form_field` (`type`, `pid`, `sorting`, `tstamp`, `label`, `name`, `text`, `html`, `options`, `mandatory`, `rgxp`, `placeholder`, `customRgxp`, `errorMsg`, `minlength`, `maxlength`, `maxImageWidth`, `maxImageHeight`, `minval`, `maxval`, `step`, `size`, `multiple`, `mSize`, `extensions`, `storeFile`, `uploadFolder`, `useHomeDir`, `doNotOverwrite`, `class`, `value`, `accesskey`, `fSize`, `customTpl`, `slabel`, `imageSubmit`, `singleSRC`, `invisible`) VALUES ('hidden', @pid, 752, 1699274640, '', 'hiddenfield', NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', 'a:2:{i:0;i:4;i:1;i:40;}', 0, 0, 'jpg,jpeg,gif,png,pdf,doc,docx,xls,xlsx,ppt,pptx', 0, NULL, 0, 0, '', 'Hidden field value', '', 0, '', '', 0, NULL, 0);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
