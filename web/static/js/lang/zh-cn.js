// 除公用的语言外， 模块内的语言请写到各模块命名空间里，插件的语言写到对应的插件命名空间里
// 使用语言包时，统一用 Ibos.l 作为入口


// 操作、状态、常用名称、动词

var L = {
    // 通用操作
    NEW: "新建",
    ADD: "增加",
    DELETE: "删除",
    MODIFY: "修改",
    EDIT: "编辑",
    SAVE: "保存",
    RENAME: "重命名",
    COPY: "复制",
    "IMPORT": "导入",
    "EXPORT": "导出",
    DOWNLOAD: "下载",
    CONFIRM: "确定",
    CANCEL: "取消",
    CLOSE: "关闭",
    FROM: "从",
    TO: "至",
    MOVEUP: "向上移动",
    MOVEDOWN: "向下移动",
    REPLY: "回复",
    MARK: "标记",
    UNMARK: "取消标记",
    START: "开始",
    END: "结束",
    SEND: "发送",
    IN_SUBMIT: "提交中，请稍等...",
    READ_INFO: "读取数据，请稍等...",
    CONFIRM_POST: "确认提交",
    CONFIRM_DEL_ATTACH: "确认要删除附件吗？",
    // 通用关键字
    SUCCESS: "成功",
    FAILED: "失败",
    CONTENT: "内容",
    DIR: "文件夹",
    PORT: "端口",
    RECEIVER: "收信人",
    SERVER_URL: "服务器地址",
    SUBJECT: "主题",
    URL: "url",
    USERNAME: "用户名",
    PASSWORD: "密码",
    REALNAME: "真实姓名",
    MOBILE: "手机",
    EMAIL: "邮箱",
    JOBNUMBER: "工号",
    STAFF: "人员",
    DEPARTMENT: "部门",
    POSITION: "职位",
    COMPANY: "公司",
    SETTING: "设置",
    ADVANCED_SETTING: "高级设置",
    HISTORICAL_EDITION: "历史版本",
    // 通用状态提示
    OPERATION_SUCCESS: "操作成功",
    OPERATION_FAILED: "操作失败",
    SAVE_SUCEESS: "保存成功",
    SAVE_FAILED: "保存失败",
    DELETE_SUCCESS: "删除成功",
    DELETE_FAILED: "删除失败",
    SETUP_SUCCEESS: "设置成功",
    SELECT_ONE_ITEM: "请选择一项来进行操作",
    SELECT_AT_LEAST_ONE_ITEM: "请选择至少一项再进行操作",
    REPLY_SUCCESS: "回复成功",
    PARAM_ERROR: "参数错误！",
    FOLLOW: "关注",
    FOLLOWING: "关注中...",
    UNFOLLOW: "取消关注",
    UNFOLLOWING: "取消中...",
    FOLLOWBOTH: "相互关注",
    FOLLOWED: "已关注",
    RECEIVER_CANNOT_BE_EMPTY: "收信人不能为空",
    CONTENT_CANNOT_BE_EMPTY: "内容不能为空",
    BEGIN_GREATER_THAN_END: "开始时间不能小于结束时间",
    TWICE_INPUT_INCONFORMITY: "两次输入不一致",
    CM: {
        NOT_AVAILABLE: "暂无",
        DEFAULT: "默认",
        MOVE_SUCCEED: "移动成功",
        MOVE_FAILED: "移动失败",
        MODIFY_SUCCEED: "修改成功",
        MODIFY_FAILED: "修改失败",
        COMPLETE: "完成",
        UNCOMPLETE: "未完成",
        LOAD_SUCCEED: "加载成功",
        LOAD_FAILED: "加载失败"
    },
    // 通用验证信息
    V: {
        INPUT_ACCOUNT: "请填入账号",
        INPUT_EMAIL: "请填入邮箱",
        INPUT_JOBNUM: "请填入工号",
        INPUT_MOBILE: "请填入手机",
        INPUT_USERNAME: "请填入用户名",
        INPUT_POSSWORD: "请填入密码",
        USERNAME_EXISTED: "用户名已存在",
        USERNAME_NOT_EXISTED: "用户名已存在",
        ACCOUNT_EXISTED: "账号已存在",
        MOBILE_EXISTED: "手机号码已存在",
        EMAIL_EXISTED: "邮箱地址已存在",
        JOBNUMBER_EXISTED: "工号已存在",
        USERNAME_VALIDATE: "请输入4-20位用户名",
        PASSWORD_INCORRECT: "密码错误",
        PASSWORD_LENGTH_RULE: "请填写<%=min%>-<%=max%>位密码",
        PASSWORD_LENGTH_RULE_REGEX: "请填写<%=min%>-<%=max%>位包含字母和数字的密码",
        PASSWORD_PREG: "请填写<%=min%>-<%=max%>位<%=mixed%>密码",
        ORIGINAL_PASSWORD_INPUT_INVALID: "原密码输入错误"
    },
    // 
    RULE: {
        NOT_NULL: "不为空",
        INVALID_FORMAT: "格式错误",
        CHINESE_ONLY: "必须为中文",
        ENGLISH_ONLY: "必须为英文",
        NUMERIC_ONLY: "必须为数字",
        CONTAIN_NUM_AND_LETTER: "需包含数字与字母",
        IDCARD_INVALID_FORMAT: "身份证格式错误",
        MOBILE_INVALID_FORMAT: "手机号码格式错误",
        MONEY_INVALID_FORMAT: "金额格式错误",
        PHONE_INVALID_FORMAT: "电话格式错误",
        ZIP_INVALID_FORMAT: "邮政编码格式错误",
        EMAIL_INVALID_FORMAT: "邮箱格式错误",
        URL_INVALID_FORMAT: "URL地址格式错误",
        // PASSWORD: "密码必须包含字母和数字",
        PASSWORD: "密码格式错误",
        PASSWORD_CANNOT_BE_EMPTY: "密码不能为空",
        REALNAME_CANNOT_BE_EMPTY: "真实姓名不能为空",
        BIRTHDAY_CANNOT_BE_EMPTY: "生日不能为空",
        ORDERID: "序号不能为空且只能为数字",
        SUBJECT_CANNOT_BE_EMPTY: "主题不能为空"
   },
    // PM
    SEND_PM: "发私信",
    NOTIFY: {
        UNREAD_TOTAL: "您有 <%=count%> 未读新提醒",
        TO_VIEW: "请点击查看"
    },
    // Dropnotify
    DN: {
        NEW_FOLOWER_COUNT: "位新粉丝",
        UNREAD_ATME: "条新@提到我",
        UNREAD_COMMENT: "条新的评论",
        UNREAD_GROUP_ATME: "条群聊@提到我",
        UNREAD_GROUP_COMMENT: "条群组评论",
        USER: "条新系统通知",
        UNREAD_MESSAGE: "条新的私信",
        DIARY: "篇新日志",
        REPORT: "篇新总结",
        CALENDAR: "条新日程",
        WORKFLOW: "条新工作流",
        ARTICLE: "篇新闻",
        OFFICIALDOC: "篇新公文",
        ASSIGNMENT: "条新任务",
        EMAIL: "封新邮件",
        MESSAGE: "条新消息",
        MEETING:"条会议管理消息",
        CAR: "条车辆管理消息",
        ASSETS:"条资产管理消息"
    },
    CNUM: {
        ONE: "一",
        TWO: "二",
        THREE: "三",
        FOUR: "四",
        FIVE: "五",
        SIX: "六",
        SEVEN: "七",
        EIGHT: "八",
        NINE: "九",
        TEN: "十",
        ELEVEN: "十一",
        TWELVE: "十二"
    },
    DATE: {
        DAYSTR: "日一二三四五六",
        MONTHSTR: "一月,二月,三月,四月,五月,六月,七月,八月,九月,十月,十一月,十二月"
    },
    TIME: {
        YEAR: "年",
        HALFYEAR: "半年",
        QUARTER: "季",
        MONTH: "月",
        WEEK: "周",
        DAY: "天",
        HOUR: "小时",
        MIN: "分",
        MINS: "分钟",
        SEC: "秒",
        SECS: "秒钟",
        INVALID_DATE: "日期格式无效",
        WEEKS: "星期",
        WEEKDAYS: "日一二三四五六"
    },
    YUANCAPITAL: {
        ZERO: "零",
        ONE: "壹",
        TWO: "贰",
        THREE: "叁",
        FOUR: "肆",
        FIVE: "伍",
        SIX: "陆",
        SEVEN: "柒",
        EIGHT: "捌",
        NINE: "玖",
        TEN: "拾",
        HUNDRED: "佰",
        THOUSAND: "仟",
        TEN_THOUSAND: "万",
        HUNDRED_MILLION: "亿",
        DOLLAR: "元",
        TEN_CENT: "角",
        CENT: "分",
        INTEGER: "整"
    },
    // UserSelect
    US: {
        CONTACT: "常用联系人",
        PER_DEPARTMENT: "按部门",
        PER_POSITION: "按岗位",
        SELECT_ALL: "选择全部",
        INPUT_TIP: "请输入部门或同事的名称或拼音",
        NO_MATCH: "没有查询结果",
        SELECTION_TO_BIG: "你最多只能选择<%=limit%>项",
        PLACEHOLDER_ALL: "请选择部门、岗位或人员",
        PLACEHOLDER_DEPARTMENT: "请选择部门",
        PLACEHOLDER_USER: "请选择人员",
        PLACEHOLDER_POSITION: "请选择岗位"
    },
    // Treemenu
    TREEMENU: {
        INPUT_CATEGORY_NAME: "请填写分类名称",
        ADD_CATELOG: "新建分类",
        ADD_CATELOG_SUCCESS: "分类添加成功",
        EDIT_CATELOG: "编辑分类",
        EDIT_CATELOG_SUCCESS: "分类保存成功",
        MOVE_CATELOG_SUCCESS: "分类移动成功",
        MOVE_CATELOG_FAILED: "分类移动失败",
        CATELOG_IS_FIRST: "该分类已是第一个分类",
        CATELOG_IS_LAST: "该分类已是最后一个分类",
        DEL_CATELOG_SUCCESS: "分类删除成功",
        DEL_CATELOG_FAILED: "分类删除失败",
        CANCEL_OPERATE: "已取消操作"
    },
    // Select2
    S2: {
        NO_MATCHES: "没有查询结果",
        SELECTION_TO_BIG: "你最多只能选择<%=count%>项",
        SEARCHING: "查询中...",
        INPUT_TO_SHORT: "还需要输入 <%=count%> 个字符",
        LOADING_MORE: "读取更多结果"
    },
    // 上传
    UPLOAD: {
        MANAGE: "附件管理",
        WAITING: "等待上传", //"上传中...",
        QUEUE_LIMIT_EXCEEDED: "已达到文件上限",
        FILE_EXCEEDS_SIZE_LIMIT: "文件太大",
        ZERO_BYTE_FILE: "不能上传零字节文件",
        INVALID_FILETYPE: "禁止上传该类型的文件",
        UNKNOWN_ERROR: "未知错误",
        UPLOAD_COMPLETE: "上传完成",
        DELETE_ATTACH: "删除附件",
        HTTP_ERROR: "上传出现错误：<%= message %>",
        MISSING_UPLOAD_URL: "未设置上传地址",
        UPLOAD_FAILED: "上传失败",
        IO_ERROR: "服务器写入错误",
        SECURITY_ERROR: "安全性错误",
        UPLOAD_LIMIT_EXCEEDED: "已达到上传文件数上限",
        SPECIFIED_FILE_ID_NOT_FOUND: "未找到要上传的文件",
        FILE_VALIDATION_FAILED: "上传过程中发生错误",
        FILE_CANCELLED: "已取消",
        UPLOAD_STOPPED: "已暂停",
        UNHANDLED_ERROR: "未处理的错误：<%= message %>",
        RESIZE: "调整大小"
    },
    DATATABLE: {
        paginate: {
            first: "首页",
            last: "末页",
            previous: "上页",
            next: "下页"
        },
        zeroRecords: "没有找到相关的记录",
        emptyTable: "<div class='tac'><img src='static/image/common/no-info.png'/></div>",
        info: "正在显示第 _START_ 到第 _END_ 条记录，共 _TOTAL_ 条记录",
        infoEmpty: "正在显示第 0 到第 0 条记录，共 0 条记录",
        infoFiltered: " - 从 _MAX_ 条记录中过滤",
        lengthMenu: "显示 _MENU_ 条记录",
        loadingRecords: "读取中..."
    },
    //更新公告显示框
    UPGRADETIP: {
        UPGRADE_ANNOUNCEMENT: "更新公告",
        LEARN_MORE: "了解更多"
    },
    // 初始化引导
    GUIDE: {
        WRITE_PHONE_NUMBER: "填写手机号码",
        WRITE_EMAIL_ADDRESS: "填写邮箱地址",
        WRITE_PERSONAL_BIRTHDAY: "填写个人生日",
        UPLOAD_REAL_AVATAR: "上传真实头像",
        JUST_A_LITTLE_MORE: "就差一点点而已",
        IMMEDIATELY_TO_FILL_OUT: "即刻去填完!",
        DATA_HAS_NOT_FILLED_OUT: "您的资料尚未填完",
        CONTINUE_TO_IMPROVE: "继续完善"
    },
    // 登录
    LOGIN: {
        LOGIN: "登录",
        TIMEOUT: "登录状态已超时，请重新登录",
        CLEAR_COOKIE_CONFIRM: "确定要清除登录痕迹吗？",
        CLEARED_COOKIE: "已清除所有登录痕迹！"
    },
    // comment
    CONFIRM_DEL_COMMENT: "确认要删除这条信息？",
    VOTE: {
        MAX_ITEM: "最多可选择<%=count%>项",
        SINGLE_ITEM: "单项",
        SELECT_ONE_AT_LEAST_OBJECT: "请至少选择一个投票项",
        HAS_VOTE_THANKS: "<p>您已经投过票，谢谢您的参与</p>",
        VOTE_TITLE: "请输入投票标题", 
        WRITE_AT_LEAST_TWO_ITEM: "请至少填写两个投票项",
    },
    CONTACT: {
        ADD_TOP_CONTACTS: "添加常用联系人",
        CANCLE_TOP_CONTACTS: "取消常用联系人",
        NOT_AVAILABLE: "暂无"
    },
    MSG: {
        NOTIFY_REMOVE_CONFIRM: "你确认删除选中提醒吗？该操作不可恢复"
    },
    // 评论
    COMMENT: {
        SUCCESS: "评论成功"
    }
};

