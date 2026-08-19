import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout from '../../Components/AppLayout';

function Index({ contacts, deleteError }) {

    const [email, setEmail] = useState('');
    const [phone, setPhone] = useState('');
    const [type, setType] = useState('');

    const [editingId, setEditingId] = useState(null);
    const [editEmail, setEditEmail] = useState('');
    const [editPhone, setEditPhone] = useState('');
    const [editType, setEditType] = useState('');

    const handleSubmit = (e) => {
    e.preventDefault();

    router.post('/contatos', {
        email: email,
        phone: phone,
        type: type,
        });
    };

    return (
        <AppLayout>
            <div className="mb-8">
                <h1 className="text-3xl font-bold tracking-tight text-slate-900">
                    Contatos
                </h1>

                <p className="mt-1 text-slate-500">
                    Gerencie seus clientes e fornecedores.
                </p>
            </div>

            {deleteError && (
                <div className="mt-6 flex items-start gap-4 rounded-xl px-5 py-4"
                    style={{
                        backgroundColor: '#fef2f2',
                        border: '1px solid #fca5a5',
                        borderLeft: '5px solid #dc2626',
                    }}
                >
                    <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full font-bold"
                        style={{
                            backgroundColor: '#fee2e2',
                            color: '#dc2626',
                        }}
                    >
                        !
                    </div>

                    <div>
                        <p className="font-semibold" style={{ color: '#991b1b' }} >
                            Não foi possível excluir o contato
                        </p>

                        <p className="mt-1 text-sm" style={{ color: '#b91c1c' }} >
                            {deleteError}
                        </p>
                    </div>
                </div>
            )}

            <form onSubmit={handleSubmit} className="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
            >
                <div className="mb-4">
                    <h2 className="text-lg font-semibold text-slate-800">
                        Novo contato
                    </h2>

                    <p className="mt-1 text-sm text-slate-500">
                        Cadastre um cliente ou fornecedor.
                    </p>
                </div>

                <div
                    style={{
                        display: 'grid',
                        gridTemplateColumns: '2fr 1fr 1fr auto',
                        gap: '12px',
                        alignItems: 'end',
                    }}
                >
                    <div>
                        <label className="mb-1 block text-sm font-medium text-slate-600">
                            E-mail
                        </label>

                        <input type="email" placeholder="contato@empresa.com" value={email} onChange={(e) => setEmail(e.target.value)} className="w-full rounded-xl border border-slate-300 px-3 py-2.5 outline-none transition focus:border-slate-900" />
                    </div>

                    <div>
                        <label className="mb-1 block text-sm font-medium text-slate-600">
                            Telefone
                        </label>

                        <input type="text" placeholder="(48) 99999-9999" value={phone} onChange={(e) => setPhone(e.target.value)} className="w-full rounded-xl border border-slate-300 px-3 py-2.5 outline-none transition focus:border-slate-900" />
                    </div>

                    <div>
                        <label className="mb-1 block text-sm font-medium text-slate-600">
                            Tipo
                        </label>

                        <select value={type} onChange={(e) => setType(e.target.value)} className="w-full rounded-xl border border-slate-300 px-3 py-2.5 outline-none transition focus:border-slate-900"
                        >
                            <option value="">Selecione</option>
                            <option value="cliente">Cliente</option>
                            <option value="fornecedor">Fornecedor</option>
                        </select>
                    </div>

                    <button type="submit" className="rounded-xl bg-emerald-600 px-5 py-2.5 font-semibold text-white transition hover:bg-emerald-500 active:scale-95" >
                        Cadastrar
                    </button>
                </div>
            </form>

            <div className="mb-8">
                <div className="mb-4 flex items-center justify-between">
                    <div>
                        <h2 className="text-lg font-semibold text-slate-800">
                            Meus contatos
                        </h2>

                        <p className="mt-1 text-sm text-slate-500">
                            Clientes e fornecedores cadastrados no sistema.
                        </p>
                    </div>

                    <span className="rounded-full bg-slate-200 px-3 py-1 text-sm font-medium text-slate-600">
                        {contacts.length} contato(s)
                    </span>
                </div>

                <div className="space-y-4">
                    {contacts.length === 0 && (
                        <div className="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center">
                            <p className="font-medium text-slate-700">
                                Nenhum contato cadastrado.
                            </p>

                            <p className="mt-1 text-sm text-slate-500">
                                Cadastre seu primeiro cliente ou fornecedor acima.
                            </p>
                        </div>
                    )}

                    {contacts.map((contact) => (
                        <div key={contact.id} className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md" >
                            {editingId === contact.id ? (
                                <div>
                                    <div
                                        style={{
                                            display: 'grid',
                                            gridTemplateColumns: '2fr 1fr 1fr',
                                            gap: '12px',
                                        }}
                                    >
                                        <input value={editEmail} onChange={(e) => setEditEmail(e.target.value)} className="rounded-xl border border-slate-300 px-3 py-2.5 outline-none focus:border-slate-900" />

                                        <input value={editPhone} onChange={(e) => setEditPhone(e.target.value)} className="rounded-xl border border-slate-300 px-3 py-2.5 outline-none focus:border-slate-900" />

                                        <select value={editType} onChange={(e) => setEditType(e.target.value)} className="rounded-xl border border-slate-300 px-3 py-2.5 outline-none focus:border-slate-900"
                                        >
                                            <option value="cliente">Cliente</option>
                                            <option value="fornecedor">Fornecedor</option>
                                        </select>
                                    </div>

                                    <div className="mt-4 flex gap-2">
                                        <button
                                            onClick={() =>
                                                router.put(
                                                    `/contatos/${contact.id}`,
                                                    {
                                                        email: editEmail,
                                                        phone: editPhone,
                                                        type: editType,
                                                    },
                                                    {
                                                        onSuccess: () => {
                                                            setEditingId(null);
                                                        },
                                                    }
                                                )
                                            }
                                            className="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700" >
                                            Salvar
                                        </button>

                                        <button onClick={() => setEditingId(null)} className="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100" >
                                            Cancelar
                                        </button>
                                    </div>
                                </div>
                            ) : (
                                <div className="flex items-start justify-between gap-4">
                                    <div>
                                        <div className="flex items-center gap-3">
                                            <h3 className="font-semibold text-slate-900">
                                                {contact.email}
                                            </h3>

                                            <span
                                                className={`rounded-full px-3 py-1 text-xs font-semibold ${
                                                    contact.type === 'cliente'
                                                        ? 'bg-blue-100 text-blue-700'
                                                        : 'bg-violet-100 text-violet-700'
                                                }`}
                                            >
                                                {contact.type === 'cliente'
                                                    ? 'Cliente'
                                                    : 'Fornecedor'}
                                            </span>
                                        </div>

                                        <p className="mt-2 text-sm text-slate-500">
                                            {contact.phone}
                                        </p>
                                    </div>

                                    <div className="flex gap-2">
                                        <button
                                            onClick={() => {
                                                setEditingId(contact.id);
                                                setEditEmail(contact.email);
                                                setEditPhone(contact.phone);
                                                setEditType(contact.type);
                                            }}
                                            className="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                                        >
                                            Editar
                                        </button>

                                        <button
                                            onClick={() => {
                                                if (!confirm('Deseja realmente excluir este contato?')) {
                                                    return;
                                                }

                                                router.delete(`/contatos/${contact.id}`);
                                            }}
                                            className="rounded-xl bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-100"
                                        >
                                            Excluir
                                        </button>
                                    </div>
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}

export default Index;