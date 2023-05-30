

import { environment } from "src/environments/environment";
const base_url = environment.apiUrlMedia;
export class UserEvento {

    id: string;
    username: string = "";
    email: string = "";
    firstName: string = "";
    lastName: string = "";
    created_at: string = "";
    role?: 'SUPERADMIN' | 'ADMIN' | 'MEMBER' | 'EDITOR' | 'GUEST';

}
