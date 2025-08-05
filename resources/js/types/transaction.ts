import { OrderItem } from './cart';

export interface Transaction {
    id: number;
    user_id: number;
    orders: OrderItem[];
    gobiz_transaction_id: number;
    total_amount: number;
    status: string;
}
