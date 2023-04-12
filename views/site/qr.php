
<div id="app">
    <div class="embed-responsive embed-responsive-1by1 bg-light">
        <video id="preview" class="embed-responsive-item"></video>
    </div>
    <div class="sidebar">
        <section class="cameras">
            <h2>Камеры</h2>
            <div v-if="cameras.length === 0" class="empty">Нет не одной доступной камеры</div>
            <div v-for="camera in cameras" :key="camera.id" class="btn btn-light">
          <span v-if="camera.id === activeCameraId" :title="formatName(camera.name)" class="active">
            {{ formatName(camera.name) }}
          </span>
                <span v-else :title="formatName(camera.name)">
            <a @click.stop="selectCamera(camera)">{{ formatName(camera.name) }}</a>
          </span>
            </div>
        </section>
        <section class="scans">
            <h2>Отсканированные QR-Коды</h2>
            <ul v-if="scans.length === 0">
                <li class="empty">Не отсканировано не одного QR-Кода</li>
            </ul>
            <transition-group name="scans" tag="ul">
                <li v-for="scan in scans" :key="scan.date" @click="sendScanToServer(scan.content)">
                    {{ scan.content }}
                </li>
            </transition-group>
        </section>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@3.2.21/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/instascan@1.0.0-beta.7/instascan.min.js"></script>

<script>
    const app = Vue.createApp({
        data() {
            return {
                scanner: null,
                activeCameraId: null,
                cameras: [],
                scans: [],
            };
        },
        mounted() {
            this.scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5 });
            this.scanner.addListener('scan', (content, image) => {
                this.scans.unshift({ date: +(Date.now()), content: content });
            });
            Instascan.Camera.getCameras().then(cameras => {
                this.cameras = cameras;
                if (cameras.length > 0) {
                    this.activeCameraId = cameras[0].id;
                    this.scanner.start(cameras[0]);
                } else {
                    console.error('Нет доступных камер.');
                }
            }).catch(error => console.error(error));
        },
        methods: {
            formatName(name) {
                return name || '(неизвестно)';
            },
            selectCamera(camera) {
                this.activeCameraId = camera.id;
                this.scanner.start(camera);
            },
            sendScanToServer(content) {
                fetch('/api/scan/create', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ content: content })
                }).then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка отправки сканирования на сервер.');
                    }
                    console.log('Сканирование отправлено на сервер.');
                }).catch(error => console.error(error));
            }
        },
        watch: {
            activeCameraId: function (val) {
                const camera = this.cameras.find(camera => camera.id === val);
                this.scanner.start(camera);
            },
            scans: function (val) {
                if (val.length > 10) {
                    this.scans.pop();
                }
            }
        }
    });

    app.mount('#app');
</script>
