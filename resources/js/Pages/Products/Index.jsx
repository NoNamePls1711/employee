import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Link, Head } from '@inertiajs/react';

export default function Index({ products }) {
    return (
        <AuthenticatedLayout>
            <Head title="Products" />
            <div className="index-backgroud">
                <h1 className="index-head-text">Meme Shop</h1>
                <div className="index-row-product">
                    {products.map((product) => (
                        <div
                            key={product.id}
                            className="index-key-prouct"
                        >
                            <img
                                src={product.image}
                                alt={product.name}
                                className="index-img"
                            />
                            <h2 className="index-textname-prouct">{product.name}</h2>
                            <p className="index-textprice-prouct">฿{product.price}</p>
                            <Link
                                href={`/products/${product.id}`}
                                className="index-product-id"
                            >
                                ดูรายละเอียด
                            </Link>
                        </div>
                    ))}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
