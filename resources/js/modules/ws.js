import Sockette from './sockette-fork';

function createWs(connHandlers) {
  // TODO: manage host

  const host = process.env.NODE_ENV === 'development'
    ? 'localhost'
    : 'digico.itech-test.ru';

  return new Sockette(`ws://${host}:2346`, {
    timeout: 2 * 6e4, // 2 min
    maxAttempts: Infinity,
    ...connHandlers
  });
}

class WsTunnel {
  constructor() {
    this.ws = createWs({
      onopen: () => {
        this.notify('ws-open');
      },
      onmessage: ({ data }) => {
        const parsedMessage = JSON.parse(data);

        this.notify(parsedMessage.type, parsedMessage);
      },
      onerror: () => {
        console.log('Error: WsTunnel error');
      },
      onclose: (e) => {
        if (e.code === 1009) {
          this.ws.reconnect(e);
        }
      }
    });

    this.subscribers = {};
  }

  subscribe(evt, cb) {
    if (!this.subscribers[evt]) {
      this.subscribers[evt] = [];
    }
    this.subscribers[evt].push(cb);

    return () => this.unsubscribe(evt, cb);
  }

  unsubscribe(evt, cb) {
    if (this.subscribers[evt] instanceof Array === false) {
      return;
    }

    this.subscribers[evt].filter(
      sub => sub !== cb
    );
  }

  notify(evt, data) {
    this.subscribers[evt].forEach(cb => cb(data));
  }

  getState() {
    return this.ws.ws.readyState;
  }

  json(data) {
    this.ws.json(data);
  }
}

export default {
  install(Vue) {
    // eslint-disable-next-line no-param-reassign
    Vue.prototype.$wsTunnel = new WsTunnel();
  }
};
