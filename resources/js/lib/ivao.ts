export class Ivao {
    static cache = new Map<string, string | null>();

    private static fetchWithAuth(url: string): Promise<Response> {
        return fetch(url, {
            headers: {
                ApiKey: import.meta.env.VITE_IVAO_API_KEY,
            },
        });
    }

    static async getAirlineLogoUrl(
        airlineIcao: string,
    ): Promise<string | null> {
        const cacheKey = `airlines/${airlineIcao}/logo`;

        if (this.cache.has(cacheKey)) {
            return this.cache.get(cacheKey)!;
        }

        const response = await this.fetchWithAuth(
            `https://api.ivao.aero/v2/airlines/${airlineIcao}/logo`,
        );

        if (!response.ok) {
            this.cache.set(cacheKey, null);

            return null;
        }

        const blob = await response.blob();
        const url = URL.createObjectURL(blob);
        this.cache.set(cacheKey, url);

        return url;
    }
}
