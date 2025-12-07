import {Route} from "../models/types";

export class AppRouter {
    private routes: Route[];
    private contentContainer: HTMLElement;
    private savedNewsCountElement: HTMLElement | null;

    constructor(routes: Route[]) {
        this.routes = routes;
        const container = document.getElementById('app-content');
        if (!container) throw new Error('Dom-Element #app-content does not exist!');
        this.contentContainer = container;
        this.savedNewsCountElement = document.getElementById('saved-count');
        window.addEventListener('hashchange', this.handleRouting.bind(this));
        window.addEventListener('DOMContentLoaded', this.handleRouting.bind(this));
        this.addNavigationListeners();
    }

    public updateSavedNewsCount(count: number): void {
        if (this.savedNewsCountElement) {
            this.savedNewsCountElement.innerText = count.toString()
        }
    }

    private addNavigationListeners() {
        document.querySelectorAll('a[data-route]').forEach(link => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                const routePath = link.getAttribute('data-route');
                if (routePath) {
                    window.location.href = routePath === '/' ? '' : '#' + routePath;
                }
            });
        });
    }

    async handleRouting() {
        let path = window.location.hash.slice(1) || '/';
        this.checkAndSetOnlineStatus();
        const [matchingRoute, params] = this.findMatchingRoute(path);
        if (matchingRoute) {
            const titleElement = document.getElementById('page-title');
            if (titleElement) titleElement.innerText = matchingRoute.title;
            try {
                this.contentContainer.innerHTML = await matchingRoute.render(params);
            } catch (error) {
                console.error('Fehler beim Rendern der Route:', error);
                this.contentContainer.innerHTML = '<h1>Fehler 500</h1><p>Inhalt konnte nicht geladen werden.</p>';
            }
        } else {
            this.contentContainer.innerHTML = '<h1>404</h1><p>Seite nicht gefunden.</p>';
            const titleElement = document.getElementById('page-title');
            if (titleElement) titleElement.innerText = 'Seite nicht gefunden';
        }
    }

    private findMatchingRoute(path: string): [Route | undefined, { id?: string } | undefined] {
        const exactMatch = this.routes.find(r => r.path === path);
        if (exactMatch) return [exactMatch, undefined];
        const dynamicRoute = this.routes.find(r => r.path.includes('/:id'));
        if (dynamicRoute && dynamicRoute.path === '/news/:id' && path.startsWith('/news/')) {
            const id = path.split('/')[2];
            if (id) {
                return [dynamicRoute, {id}];
            }
        }
        return [undefined, undefined];
    }

    private checkAndSetOnlineStatus(): void {
        const offlineStatus = document.getElementById('offline-status');
        if (offlineStatus) {
            offlineStatus.style.display = navigator.onLine ? 'none' : 'block';
        }
    }
}
