$.extend($.validator.messages, {
    required: "必填*",
    remote: "请修正该字段",
    email: "请输入正确格式的电子邮件",
    url: "请输入合法的网址",
    date: "请输入合法的日期",
    dateISO: "请输入合法的日期 (ISO).",
    number: "请输入合法的数字",
    digits: "请输入短信验证码",
    creditcard: "请输入合法的信用卡号",
    equalTo: "两次输入的密码不一致",
    accept: "请输入拥有合法后缀名的字符串",
    maxlength: $.validator.format("用户名最多为 {0} 位"),
    minlength: $.validator.format("用户名最少为 {0} 位"),
    rangelength: $.validator.format("请输入长度为 {0} - {1} 的密码"),
    range: $.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
    max: $.validator.format("请输入一个最大为 {0} 的值"),
    min: $.validator.format("请输入一个最小为 {0} 的值")
});

