import Sockette from 'sockette';

function createWs(connHandlers) {
  return new Sockette('ws://localhost:2346', {
    timeout: 2 * 6e4, // 2 min
    maxAttempts: Infinity,
    ...connHandlers
  });
}

class WsTunnel {
  constructor() {
    this.ws = createWs({
      onmessage: ({ data }) => {
        this.notify(data.type, JSON.parse(data));
      },
      onerror: () => {
        console.log('Error: WsTunnel onerror callback');
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

  sendLastNotificationId(uuid) {
    this.ws.json({
      type: 'notification',
      data: {
        type: 'all-read',
        lastId: uuid
      }
    });
  }
}

export default {
  install(Vue) {
    // eslint-disable-next-line no-param-reassign
    Vue.prototype.$wsTunnel = new WsTunnel();
  }
};
