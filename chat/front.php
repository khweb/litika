<?php 


function acc_chat_content() {
	echo '<h3>Администрация</h3>';

	// Записываем информацию про юзера в сессию
	$user = wp_get_current_user();
	$userID = $user->ID;
	$root = current_user_can( 'edit_user' ) ? 1 : 0;
	$userName = $user->display_name;
	$avatar = get_avatar_url($user->ID);
	// Конец
	?>
	<!--APP-->
	<div id="chatApp" class="chatApp">
		<div class="chatApp__loaderWrapper">
			<div class="chatApp__loaderText">Загрузка...</div>
			<div class="chatApp__loader"></div>
		</div>
	</div>
	<!--end APP-->
	
<script src="https://fb.me/react-with-addons-15.1.0.js"></script>
<script src="https://fb.me/react-dom-15.1.0.js"></script>
<script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

<script type="text/babel">
/* Функция создания ссылки в чате */
function detectURL(message) {
	
	var urlRegex = /(((https?:\/\/)|(www\.))[^\s]+)/g;
	return message.replace(urlRegex, function(urlMatch) {
		return '<a href="' + urlMatch + '">' + urlMatch + '</a>';
	})
}

class Title extends React.Component {
	constructor(props, context) {
		super(props, context);
	}
	render() {
		return (
			<div className={"chatApp__convTitle"}>{this.props.owner}</div>
		);
	}
}

class InputMessage extends React.Component {
	constructor(props, context) {
		super(props, context);
		this.handleSendMessage = this.handleSendMessage.bind(this);
	}
	handleSendMessage(e) {
		e.preventDefault();
		if( this.messageInput.value.length > 0 ) {
			this.props.sendMessageLoading(this.props.userTo, this.messageInput.value);
			this.messageInput.value = '';
		}
	}


	render() {
		/* If the chatbox state is loading, loading class for display */
		var loadingClass = this.props.isLoading ? 'chatApp__convButton--loading' : '';
		let sendButtonIcon = <i className={"fa fa-paper-plane"}></i>;
		return (
			<form onSubmit={this.handleSendMessage}>
				<input
					type="text"
					ref={message => (this.messageInput = message)}
					className={"chatApp__convInput"}
					placeholder="Текс сообщения"
					tabIndex="0"
				/>
				<div className={'chatApp__convButton ' + loadingClass} onClick={this.handleSendMessage}>
				{sendButtonIcon}
				</div>
			</form>
		);
	}
}

class MessageList extends React.Component {
	constructor(props, context) {
		super(props, context);
	}
	render() {
		return (
			<div className={"chatApp__convTimeline"}>
			{this.props.messages.slice(0).reverse().map(
				messageItem => (
					<MessageItem
						key={messageItem.id}
						owner={this.props.user}
						sender={messageItem.userFrom}
						message={messageItem.message}
						userTo={this.props.userTo}
						userToAvatar={this.props.userToAvatar}
					/>
				)
			)}
			</div>
		);
	}
}

class MessageItem extends React.Component {
	render() {
		let id     = this.props.owner.ID;
		let sender = this.props.sender;
		let messagePosition = (( id == sender ) ? 
		'chatApp__convMessageItem--right' : 
		'chatApp__convMessageItem--left');
		let avatar = ((id == sender) ? 
		this.props.owner.avatar :
		this.props.userToAvatar);
		return (
			<div className={"chatApp__convMessageItem " + messagePosition + " clearfix"}>
				<img src={avatar} alt={this.props.owner.name} className="chatApp__convMessageAvatar" />
				<div className="chatApp__convMessageValue" dangerouslySetInnerHTML={{__html: detectURL(this.props.message)}}></div>
			</div>
		);
	}
}

/* ChatBox component - composed of Title, MessageList, TypingIndicator, InputMessage */
class ChatBox extends React.Component {
	constructor(props, context) {
		super(props, context);
		this.state = {
			isLoading: false,
		};
		this.sendMessageLoading = this.sendMessageLoading.bind(this);
		this.messageList = [];
		this.props.messages.map((item)=>{
			if (
				this.props.userTo == item.userFrom || 
				this.props.userTo == item.userTo) {
				this.messageList.push(item);
			}
		});
		
	}
	shouldComponentUpdate() {
		this.messageList = [];
		this.props.messages.map((item)=>{
			(this.props.userTo == item.userFrom || this.props.userTo == item.userTo) && this.messageList.push(item);
		});
		return true;
	}

	sendMessageLoading(sender, message) {
		this.setState({ isLoading: true });
		this.props.sendMessage(sender, message);
		setTimeout(() => {
			this.setState({ isLoading: false });
		}, 400);
	}
	render() {
		this.messageList = this.messageList.length == 0 ? this.props.messages : this.messageList;

		return (
			<div className={"chatApp__conv col-lg-6"}>
				<Title
					owner={this.props.title}
				/>
				<MessageList
					messages={this.messageList}
					user={this.props.user}
					userTo={this.props.userTo}
					userToAvatar={this.props.userToAvatar}
				/>
				<div className={"chatApp__convSendMessage clearfix"}>
					<InputMessage
						isLoading={this.state.isLoading}
						user={this.props.user}
						userTo={this.props.userTo}
						sendMessage={this.props.sendMessage}
						sendMessageLoading={this.sendMessageLoading}
					/>
				</div>
			</div>
		);
	}
}

class ChatRoom extends React.Component {
	constructor(props, context) {
		super(props, context);
		this.state = {
			messages: [],
			user: [],
			userTo: [],
			isTyping: [],
			root: ''
		};
		this.sendMessage = this.sendMessage.bind(this);
		
		fetch('https://devlpu.ru/litika/wp-admin/admin-ajax.php', {
			method: 'POST',
			credentials: 'same-origin',
			headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
			body: 'action=user_get_msg&userID=<?= $userID; ?>&root=<?= $root; ?>&userName=<?= $userName; ?>&avatar=<?= $avatar; ?>'
		})
		.then((resp) => resp.json())
		.then((data) => this.setState({
			messages: data.items, 
			user: data.user,
			userTo: data.userTo,
			root: data.root }))
		.catch(function(error) {
			console.log('Ошибка - ' + error);
		});
	}
	componentDidMount() {
		this.state.messages == [] &&
    setInterval(() => {
			fetch('https://devlpu.ru/litika/wp-admin/admin-ajax.php', {
				method: 'POST',
				credentials: 'same-origin',
				headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
				body: 'action=user_get_msg&userID=<?= $userID; ?>&root=<?= $root; ?>&userName=<?= $userName; ?>&avatar=<?= $avatar; ?>'
			})
			.then((resp) => resp.json())
			.then((data) => this.setState({
				messages: data.items, 
				user: data.user,
				userTo: data.userTo,
				root: data.root }))
			.catch(function(error) {
				console.log('Ошибка - ' + error);
			});
		}, 2500);
	}
	/* adds a new message to the chatroom */
	sendMessage(sender, message) {
		setTimeout(() => {
			
			let messageFormat = message;
			let newMessageItem = {
				userFrom: sender,
				userTo: this.state.root ? sender : 1,
				message: messageFormat
			};
			fetch('https://devlpu.ru/litika/wp-admin/admin-ajax.php', {
				method: 'POST',
				credentials: 'same-origin',
				headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
				body: 'action=user_post_msg&userID=<?= $userID; ?>&message=' + newMessageItem.message + '&root=<?= $root; ?>&userName=<?= $userName; ?>&avatar=<?= $avatar; ?>&userTo=' + newMessageItem.userTo,
			})
			.then((resp) => resp.json())
			.then((data) => this.setState({ messages: data.items }))
			.catch(function(error) {
				console.log('Ошибка - ' + error);
			});
		}, 400);
		
	}

	render() {
		let users = this.state.userTo;
		let chatBoxes = [];
		if (this.state.root == 1) {
			if (users) {
				users.map((item, key)=> {
					chatBoxes.push(
						<ChatBox
							key={key}
							sendMessage={this.sendMessage}
							messages={this.state.messages}
							user={this.state.user}
							userTo={item.id}
							userToAvatar={item.avatar}
							title={item.name}
						/>
					);
				})
			}else {
				chatBoxes = 'Сообщений нет'
			}
		}else {
			let userToID = this.state.userTo[0] && this.state.userTo[0].id;
			let userToAvatar = this.state.userTo[0] && this.state.userTo[0].avatar;
			let userToName = this.state.userTo[0] && this.state.userTo[0].name;
			chatBoxes = <ChatBox
			sendMessage={this.sendMessage}
			messages={this.state.messages}
			user={this.state.user}
			userTo={userToID}
			userToAvatar={userToAvatar}
			title={'Задать вопрос'}
			/>
		}
		return (
			<div className={"chatApp__room row"}>
				{chatBoxes}
			</div>
		);
	}
}

/* end ChatRoom component */

/* render the chatroom */
if (jQuery('*').is('#chatApp')) {
	ReactDOM.render(<ChatRoom />, document.getElementById("chatApp"));
}

</script>

<?php }