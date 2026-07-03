import { useState } from "react";
import {
  LayoutDashboard,
  BookOpen,
  Users,
  BookCopy,
  ArrowLeftRight,
  CornerDownLeft,
  UserCheck,
  ShoppingCart,
  ClipboardList,
  AlertTriangle,
  BarChart2,
  Settings,
  ChevronDown,
  ChevronRight,
  Search,
  Bell,
  LogOut,
  Menu,
  X,
  TrendingUp,
  TrendingDown,
  Clock,
  CheckCircle2,
  AlertCircle,
  Plus,
  Upload,
  Download,
  Eye,
  Filter,
} from "lucide-react";
import {
  AreaChart,
  Area,
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
  PieChart,
  Pie,
  Cell,
  Legend,
} from "recharts";

// ── Types ─────────────────────────────────────────────────────────────────────
type NavItem = {
  id: string;
  label: string;
  icon: React.ReactNode;
  badge?: number;
  children?: { id: string; label: string }[];
};

type Page = string;

// ── Data ──────────────────────────────────────────────────────────────────────
const visitorData = [
  { day: "Sen", kunjungan: 42 },
  { day: "Sel", kunjungan: 38 },
  { day: "Rab", kunjungan: 55 },
  { day: "Kam", kunjungan: 47 },
  { day: "Jum", kunjungan: 61 },
  { day: "Sab", kunjungan: 29 },
];

const borrowingTrend = [
  { bulan: "Jan", pinjam: 120, kembali: 108 },
  { bulan: "Feb", pinjam: 145, kembali: 130 },
  { bulan: "Mar", pinjam: 162, kembali: 150 },
  { bulan: "Apr", pinjam: 138, kembali: 145 },
  { bulan: "Mei", pinjam: 175, kembali: 168 },
  { bulan: "Jun", pinjam: 190, kembali: 172 },
  { bulan: "Jul", pinjam: 84, kembali: 79 },
];

const categoryData = [
  { name: "Agama", value: 312, color: "#145048" },
  { name: "IPA", value: 248, color: "#2a8a76" },
  { name: "IPS", value: 195, color: "#bf7c33" },
  { name: "Bahasa", value: 167, color: "#5ba89a" },
  { name: "Umum", value: 143, color: "#e8a44a" },
];

const recentBorrowings = [
  { kode: "BRW-20260702-0018", anggota: "Ahmad Fauzi", kelas: "VIII-B", buku: "Tafsir Al-Muyassar", tgl: "02 Jul 2026", jatuh: "09 Jul 2026", status: "Dipinjam" },
  { kode: "BRW-20260702-0017", anggota: "Siti Nurhaliza", kelas: "VII-A", buku: "IPA Terpadu Kelas 7", tgl: "02 Jul 2026", jatuh: "09 Jul 2026", status: "Dipinjam" },
  { kode: "BRW-20260701-0016", anggota: "Budi Santoso", kelas: "IX-C", buku: "Bahasa Indonesia Kelas 9", tgl: "01 Jul 2026", jatuh: "08 Jul 2026", status: "Terlambat" },
  { kode: "BRW-20260701-0015", anggota: "Dewi Rahayu", kelas: "VIII-A", buku: "Matematika Kelas 8", tgl: "01 Jul 2026", jatuh: "08 Jul 2026", status: "Dipinjam" },
  { kode: "BRW-20260630-0014", anggota: "Rizki Hidayat", kelas: "VII-C", buku: "Atlas Indonesia", tgl: "30 Jun 2026", jatuh: "07 Jul 2026", status: "Terlambat" },
  { kode: "BRW-20260630-0013", anggota: "Nur Aisyah", kelas: "IX-A", buku: "PKn Kelas 9", tgl: "30 Jun 2026", jatuh: "07 Jul 2026", status: "Dikembalikan" },
];

const recentBooks = [
  { kode: "INV00312", judul: "Tafsir Al-Muyassar Jilid 2", kategori: "Agama", penerbit: "Pustaka Imam Syafi'i", tahun: 2024, kondisi: "Baik", status: "Tersedia" },
  { kode: "INV00311", judul: "IPA Terpadu SMP Kelas 8", kategori: "IPA", penerbit: "Erlangga", tahun: 2023, kondisi: "Baik", status: "Dipinjam" },
  { kode: "INV00310", judul: "Ensiklopedi Sains Islam", kategori: "Agama", penerbit: "Mizan", tahun: 2022, kondisi: "Baik", status: "Tersedia" },
  { kode: "INV00309", judul: "Matematika SMP Kelas 9", kategori: "Matematika", penerbit: "Kemendikbud", tahun: 2023, kondisi: "Rusak Ringan", status: "Tersedia" },
  { kode: "INV00308", judul: "Kamus Besar Bahasa Indonesia", kategori: "Bahasa", penerbit: "Balai Pustaka", tahun: 2021, kondisi: "Baik", status: "Tersedia" },
];

const recentMembers = [
  { kode: "MBR-2026-0148", nis: "2024.0148", nama: "Ahmad Fauzi Ramadhan", kelas: "VIII-B", jenis: "Siswa", status: "Aktif", bergabung: "15 Jul 2024" },
  { kode: "MBR-2026-0147", nis: "2024.0147", nama: "Siti Nurhaliza Putri", kelas: "VII-A", jenis: "Siswa", status: "Aktif", bergabung: "15 Jul 2024" },
  { kode: "MBR-2026-0146", nis: "2024.0146", nama: "Muhammad Rizki Hidayat", kelas: "VII-C", jenis: "Siswa", status: "Aktif", bergabung: "15 Jul 2024" },
  { kode: "MBR-GTK-008", nis: "-", nama: "Drs. Hendra Kusuma", kelas: "-", jenis: "Guru", status: "Aktif", bergabung: "01 Jan 2022" },
  { kode: "MBR-GTK-009", nis: "-", nama: "Nining Rahayu, S.Pd", kelas: "-", jenis: "Guru", status: "Aktif", bergabung: "01 Jan 2022" },
];

// ── Nav Config ────────────────────────────────────────────────────────────────
const navGroups: { label: string; items: NavItem[] }[] = [
  {
    label: "Utama",
    items: [
      { id: "dashboard", label: "Dashboard", icon: <LayoutDashboard size={16} /> },
    ],
  },
  {
    label: "Koleksi",
    items: [
      {
        id: "buku",
        label: "Buku",
        icon: <BookOpen size={16} />,
        children: [
          { id: "buku-master", label: "Katalog Buku" },
          { id: "buku-eksemplar", label: "Eksemplar" },
          { id: "pengadaan", label: "Pengadaan" },
          { id: "insiden", label: "Insiden Buku" },
          { id: "stok-opname", label: "Stok Opname" },
        ],
      },
    ],
  },
  {
    label: "Sirkulasi",
    items: [
      { id: "anggota", label: "Anggota", icon: <Users size={16} />, badge: 0 },
      { id: "pengunjung", label: "Pengunjung", icon: <UserCheck size={16} /> },
      { id: "peminjaman", label: "Peminjaman", icon: <ArrowLeftRight size={16} />, badge: 3 },
      { id: "pengembalian", label: "Pengembalian", icon: <CornerDownLeft size={16} /> },
    ],
  },
  {
    label: "Laporan",
    items: [
      { id: "laporan", label: "Laporan", icon: <BarChart2 size={16} /> },
    ],
  },
  {
    label: "Sistem",
    items: [
      { id: "pengaturan", label: "Pengaturan", icon: <Settings size={16} /> },
    ],
  },
];

// ── Helpers ───────────────────────────────────────────────────────────────────
function StatusBadge({ status }: { status: string }) {
  const map: Record<string, string> = {
    Dipinjam: "bg-blue-50 text-blue-700 border border-blue-200",
    Terlambat: "bg-red-50 text-red-700 border border-red-200",
    Dikembalikan: "bg-emerald-50 text-emerald-700 border border-emerald-200",
    Tersedia: "bg-emerald-50 text-emerald-700 border border-emerald-200",
    Rusak_Ringan: "bg-amber-50 text-amber-700 border border-amber-200",
    "Rusak Ringan": "bg-amber-50 text-amber-700 border border-amber-200",
    Aktif: "bg-emerald-50 text-emerald-700 border border-emerald-200",
    Siswa: "bg-sky-50 text-sky-700 border border-sky-200",
    Guru: "bg-purple-50 text-purple-700 border border-purple-200",
  };
  return (
    <span className={`inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${map[status] ?? "bg-gray-100 text-gray-600"}`}>
      {status}
    </span>
  );
}

function StatCard({
  label,
  value,
  sub,
  trend,
  trendUp,
  icon,
  accent,
}: {
  label: string;
  value: string;
  sub: string;
  trend?: string;
  trendUp?: boolean;
  icon: React.ReactNode;
  accent: string;
}) {
  return (
    <div className="bg-card rounded-lg border border-border p-4 flex flex-col gap-3 hover:shadow-md transition-shadow duration-200">
      <div className="flex items-start justify-between">
        <div className={`w-10 h-10 rounded-lg flex items-center justify-center text-white ${accent}`}>
          {icon}
        </div>
        {trend && (
          <span className={`flex items-center gap-0.5 text-xs font-medium ${trendUp ? "text-emerald-600" : "text-red-500"}`}>
            {trendUp ? <TrendingUp size={12} /> : <TrendingDown size={12} />}
            {trend}
          </span>
        )}
      </div>
      <div>
        <div className="text-2xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
          {value}
        </div>
        <div className="text-sm font-medium text-muted-foreground mt-0.5">{label}</div>
      </div>
      <div className="text-xs text-muted-foreground border-t border-border pt-2">{sub}</div>
    </div>
  );
}

// ── Pages ─────────────────────────────────────────────────────────────────────
function DashboardPage() {
  return (
    <div className="space-y-6">
      {/* Page header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
            Dashboard
          </h1>
          <p className="text-sm text-muted-foreground mt-0.5">Rabu, 02 Juli 2026 — Selamat datang, Admin</p>
        </div>
        <div className="flex items-center gap-2">
          <button className="flex items-center gap-2 px-3 py-2 text-sm bg-card border border-border rounded-lg hover:bg-secondary transition-colors text-foreground">
            <Download size={14} />
            Ekspor
          </button>
          <button className="flex items-center gap-2 px-3 py-2 text-sm bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity">
            <Plus size={14} />
            Peminjaman Baru
          </button>
        </div>
      </div>

      {/* Stat cards */}
      <div className="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <StatCard
          label="Total Koleksi"
          value="1.065"
          sub="312 judul · 1.065 eksemplar"
          trend="+12 bulan ini"
          trendUp
          icon={<BookOpen size={18} />}
          accent="bg-[#145048]"
        />
        <StatCard
          label="Anggota Aktif"
          value="487"
          sub="468 siswa · 19 guru/staff"
          trend="+8 pekan ini"
          trendUp
          icon={<Users size={18} />}
          accent="bg-[#2a8a76]"
        />
        <StatCard
          label="Sedang Dipinjam"
          value="73"
          sub="3 terlambat dikembalikan"
          trend="3 terlambat"
          trendUp={false}
          icon={<BookCopy size={18} />}
          accent="bg-[#bf7c33]"
        />
        <StatCard
          label="Pengunjung Hari Ini"
          value="47"
          sub="Kamis, 02 Jul 2026"
          trend="+15% vs kemarin"
          trendUp
          icon={<UserCheck size={18} />}
          accent="bg-[#5ba89a]"
        />
      </div>

      {/* Alert strip */}
      <div className="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 text-sm text-amber-800">
        <AlertCircle size={16} className="shrink-0 text-amber-600" />
        <span>
          <strong>3 buku terlambat dikembalikan.</strong> Harap hubungi anggota terkait sebelum batas waktu habis.
        </span>
        <button className="ml-auto text-xs font-medium underline whitespace-nowrap hover:no-underline">Lihat semua</button>
      </div>

      {/* Charts row */}
      <div className="grid grid-cols-1 gap-4 lg:grid-cols-3">
        {/* Borrowing trend */}
        <div className="lg:col-span-2 bg-card border border-border rounded-lg p-4">
          <div className="flex items-center justify-between mb-4">
            <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
              Tren Peminjaman &amp; Pengembalian
            </h2>
            <span className="text-xs text-muted-foreground">Jan — Jul 2026</span>
          </div>
          <ResponsiveContainer width="100%" height={200}>
            <BarChart data={borrowingTrend} barGap={4}>
              <CartesianGrid strokeDasharray="3 3" stroke="rgba(0,0,0,0.06)" vertical={false} />
              <XAxis dataKey="bulan" tick={{ fontSize: 11, fill: "#6b7a77" }} axisLine={false} tickLine={false} />
              <YAxis tick={{ fontSize: 11, fill: "#6b7a77" }} axisLine={false} tickLine={false} />
              <Tooltip
                contentStyle={{ borderRadius: "6px", border: "1px solid rgba(0,0,0,0.08)", fontSize: 12 }}
                cursor={{ fill: "rgba(0,0,0,0.03)" }}
              />
              <Bar key="pinjam" dataKey="pinjam" name="Dipinjam" fill="#145048" radius={[3, 3, 0, 0]} />
              <Bar key="kembali" dataKey="kembali" name="Dikembalikan" fill="#5ba89a" radius={[3, 3, 0, 0]} />
            </BarChart>
          </ResponsiveContainer>
          <div className="flex items-center gap-4 mt-2">
            <span className="flex items-center gap-1.5 text-xs text-muted-foreground"><span className="w-2.5 h-2.5 rounded-sm bg-[#145048] inline-block" /> Dipinjam</span>
            <span className="flex items-center gap-1.5 text-xs text-muted-foreground"><span className="w-2.5 h-2.5 rounded-sm bg-[#5ba89a] inline-block" /> Dikembalikan</span>
          </div>
        </div>

        {/* Category pie */}
        <div className="bg-card border border-border rounded-lg p-4">
          <h2 className="text-sm font-semibold text-foreground mb-4" style={{ fontFamily: "'Roboto Slab', serif" }}>
            Koleksi per Kategori
          </h2>
          <ResponsiveContainer width="100%" height={160}>
            <PieChart>
              <Pie data={categoryData} cx="50%" cy="50%" innerRadius={45} outerRadius={70} paddingAngle={2} dataKey="value">
                {categoryData.map((entry) => (
                  <Cell key={`cell-${entry.name}`} fill={entry.color} />
                ))}
              </Pie>
              <Tooltip contentStyle={{ borderRadius: "6px", border: "1px solid rgba(0,0,0,0.08)", fontSize: 12 }} />
            </PieChart>
          </ResponsiveContainer>
          <div className="space-y-1 mt-2">
            {categoryData.map((c) => (
              <div key={c.name} className="flex items-center justify-between text-xs">
                <span className="flex items-center gap-1.5 text-muted-foreground">
                  <span className="w-2 h-2 rounded-full inline-block" style={{ background: c.color }} />
                  {c.name}
                </span>
                <span className="font-medium text-foreground">{c.value}</span>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Visitor chart + quick actions */}
      <div className="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div className="bg-card border border-border rounded-lg p-4">
          <h2 className="text-sm font-semibold text-foreground mb-4" style={{ fontFamily: "'Roboto Slab', serif" }}>
            Pengunjung Minggu Ini
          </h2>
          <ResponsiveContainer width="100%" height={130}>
            <AreaChart data={visitorData}>
              <defs>
                <linearGradient id="vGrad" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="5%" stopColor="#145048" stopOpacity={0.2} />
                  <stop offset="95%" stopColor="#145048" stopOpacity={0} />
                </linearGradient>
              </defs>
              <CartesianGrid strokeDasharray="3 3" stroke="rgba(0,0,0,0.06)" vertical={false} />
              <XAxis dataKey="day" tick={{ fontSize: 11, fill: "#6b7a77" }} axisLine={false} tickLine={false} />
              <YAxis tick={{ fontSize: 11, fill: "#6b7a77" }} axisLine={false} tickLine={false} />
              <Tooltip contentStyle={{ borderRadius: "6px", border: "1px solid rgba(0,0,0,0.08)", fontSize: 12 }} />
              <Area key="kunjungan" type="monotone" dataKey="kunjungan" stroke="#145048" strokeWidth={2} fill="url(#vGrad)" dot={{ r: 3, fill: "#145048" }} />
            </AreaChart>
          </ResponsiveContainer>
        </div>

        {/* Quick actions */}
        <div className="lg:col-span-2 bg-card border border-border rounded-lg p-4">
          <h2 className="text-sm font-semibold text-foreground mb-4" style={{ fontFamily: "'Roboto Slab', serif" }}>
            Aksi Cepat
          </h2>
          <div className="grid grid-cols-2 gap-3 sm:grid-cols-3">
            {[
              { label: "Catat Peminjaman", icon: <ArrowLeftRight size={18} />, color: "bg-[#145048] text-white" },
              { label: "Catat Pengembalian", icon: <CornerDownLeft size={18} />, color: "bg-[#2a8a76] text-white" },
              { label: "Daftar Pengunjung", icon: <UserCheck size={18} />, color: "bg-[#5ba89a] text-white" },
              { label: "Tambah Buku", icon: <BookOpen size={18} />, color: "bg-[#bf7c33] text-white" },
              { label: "Import Anggota", icon: <Upload size={18} />, color: "bg-secondary text-secondary-foreground border border-border" },
              { label: "Cetak Laporan", icon: <Download size={18} />, color: "bg-secondary text-secondary-foreground border border-border" },
            ].map((a) => (
              <button
                key={a.label}
                className={`flex flex-col items-center justify-center gap-2 p-3 rounded-lg text-xs font-medium hover:opacity-90 transition-opacity ${a.color}`}
              >
                {a.icon}
                {a.label}
              </button>
            ))}
          </div>
        </div>
      </div>

      {/* Recent borrowings table */}
      <div className="bg-card border border-border rounded-lg overflow-hidden">
        <div className="flex items-center justify-between px-4 py-3 border-b border-border">
          <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
            Transaksi Terbaru
          </h2>
          <button className="text-xs text-primary font-medium hover:underline">Lihat semua →</button>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead>
              <tr className="bg-muted/40">
                <th className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground">Kode Transaksi</th>
                <th className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground">Anggota</th>
                <th className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground hidden md:table-cell">Judul Buku</th>
                <th className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground hidden lg:table-cell">Tgl Pinjam</th>
                <th className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground hidden lg:table-cell">Jatuh Tempo</th>
                <th className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground">Status</th>
                <th className="px-4 py-2.5" />
              </tr>
            </thead>
            <tbody>
              {recentBorrowings.map((row, i) => (
                <tr key={i} className="border-t border-border hover:bg-muted/20 transition-colors">
                  <td className="px-4 py-3 font-mono text-xs text-muted-foreground">{row.kode}</td>
                  <td className="px-4 py-3">
                    <div className="font-medium text-foreground text-xs">{row.anggota}</div>
                    <div className="text-xs text-muted-foreground">{row.kelas}</div>
                  </td>
                  <td className="px-4 py-3 text-xs text-foreground hidden md:table-cell max-w-[200px] truncate">{row.buku}</td>
                  <td className="px-4 py-3 text-xs text-muted-foreground hidden lg:table-cell">{row.tgl}</td>
                  <td className="px-4 py-3 text-xs hidden lg:table-cell">
                    <span className={row.status === "Terlambat" ? "text-red-600 font-medium" : "text-muted-foreground"}>{row.jatuh}</span>
                  </td>
                  <td className="px-4 py-3"><StatusBadge status={row.status} /></td>
                  <td className="px-4 py-3">
                    <button className="text-muted-foreground hover:text-primary transition-colors">
                      <Eye size={14} />
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}

function BukuPage() {
  const [search, setSearch] = useState("");
  const filtered = recentBooks.filter(
    (b) =>
      b.judul.toLowerCase().includes(search.toLowerCase()) ||
      b.kode.toLowerCase().includes(search.toLowerCase())
  );
  return (
    <div className="space-y-5">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
            Katalog Buku
          </h1>
          <p className="text-sm text-muted-foreground mt-0.5">312 judul · 1.065 eksemplar</p>
        </div>
        <div className="flex items-center gap-2">
          <button className="flex items-center gap-2 px-3 py-2 text-sm bg-card border border-border rounded-lg hover:bg-secondary transition-colors text-foreground">
            <Upload size={14} />
            Import
          </button>
          <button className="flex items-center gap-2 px-3 py-2 text-sm bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity">
            <Plus size={14} />
            Tambah Buku
          </button>
        </div>
      </div>

      <div className="bg-card border border-border rounded-lg overflow-hidden">
        <div className="flex items-center gap-3 px-4 py-3 border-b border-border">
          <div className="flex items-center gap-2 flex-1 bg-input-background border border-border rounded-lg px-3 py-2">
            <Search size={14} className="text-muted-foreground" />
            <input
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              placeholder="Cari judul, kode, ISBN..."
              className="bg-transparent text-sm outline-none flex-1 text-foreground placeholder:text-muted-foreground"
            />
          </div>
          <button className="flex items-center gap-2 px-3 py-2 text-sm bg-card border border-border rounded-lg hover:bg-secondary transition-colors text-foreground">
            <Filter size={14} />
            Filter
          </button>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead>
              <tr className="bg-muted/40">
                {["Kode Inv.", "Judul", "Kategori", "Penerbit", "Tahun", "Kondisi", "Status", ""].map((h) => (
                  <th key={h} className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground whitespace-nowrap">
                    {h}
                  </th>
                ))}
              </tr>
            </thead>
            <tbody>
              {filtered.map((row, i) => (
                <tr key={i} className="border-t border-border hover:bg-muted/20 transition-colors">
                  <td className="px-4 py-3 font-mono text-xs text-muted-foreground">{row.kode}</td>
                  <td className="px-4 py-3 font-medium text-xs text-foreground max-w-[220px] truncate">{row.judul}</td>
                  <td className="px-4 py-3 text-xs text-muted-foreground">{row.kategori}</td>
                  <td className="px-4 py-3 text-xs text-muted-foreground hidden md:table-cell">{row.penerbit}</td>
                  <td className="px-4 py-3 text-xs text-muted-foreground hidden lg:table-cell">{row.tahun}</td>
                  <td className="px-4 py-3 hidden lg:table-cell"><StatusBadge status={row.kondisi} /></td>
                  <td className="px-4 py-3"><StatusBadge status={row.status} /></td>
                  <td className="px-4 py-3">
                    <button className="text-muted-foreground hover:text-primary transition-colors">
                      <Eye size={14} />
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        <div className="flex items-center justify-between px-4 py-3 border-t border-border text-xs text-muted-foreground">
          <span>Menampilkan {filtered.length} dari 1.065 eksemplar</span>
          <div className="flex items-center gap-1">
            {[1, 2, 3, "...", 21].map((p, i) => (
              <button
                key={i}
                className={`w-7 h-7 rounded text-xs font-medium transition-colors ${p === 1 ? "bg-primary text-primary-foreground" : "hover:bg-muted"}`}
              >
                {p}
              </button>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}

function AnggotaPage() {
  const [search, setSearch] = useState("");
  const filtered = recentMembers.filter(
    (m) => m.nama.toLowerCase().includes(search.toLowerCase()) || m.kode.toLowerCase().includes(search.toLowerCase())
  );
  return (
    <div className="space-y-5">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
            Anggota Perpustakaan
          </h1>
          <p className="text-sm text-muted-foreground mt-0.5">487 anggota aktif · Terakhir diperbarui 15 Jul 2024</p>
        </div>
        <div className="flex items-center gap-2">
          <button className="flex items-center gap-2 px-3 py-2 text-sm bg-card border border-border rounded-lg hover:bg-secondary transition-colors text-foreground">
            <Upload size={14} />
            Import Excel
          </button>
          <button className="flex items-center gap-2 px-3 py-2 text-sm bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity">
            <Plus size={14} />
            Tambah Anggota
          </button>
        </div>
      </div>

      <div className="grid grid-cols-3 gap-3">
        {[
          { label: "Siswa", value: "468", color: "bg-sky-50 border-sky-200 text-sky-800" },
          { label: "Guru", value: "15", color: "bg-purple-50 border-purple-200 text-purple-800" },
          { label: "Staff", value: "4", color: "bg-orange-50 border-orange-200 text-orange-800" },
        ].map((s) => (
          <div key={s.label} className={`rounded-lg border px-4 py-3 ${s.color}`}>
            <div className="text-xl font-bold" style={{ fontFamily: "'Roboto Slab', serif" }}>{s.value}</div>
            <div className="text-xs font-medium mt-0.5">{s.label}</div>
          </div>
        ))}
      </div>

      <div className="bg-card border border-border rounded-lg overflow-hidden">
        <div className="flex items-center gap-3 px-4 py-3 border-b border-border">
          <div className="flex items-center gap-2 flex-1 bg-input-background border border-border rounded-lg px-3 py-2">
            <Search size={14} className="text-muted-foreground" />
            <input
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              placeholder="Cari nama, NIS, kode anggota..."
              className="bg-transparent text-sm outline-none flex-1 text-foreground placeholder:text-muted-foreground"
            />
          </div>
          <button className="flex items-center gap-2 px-3 py-2 text-sm bg-card border border-border rounded-lg hover:bg-secondary transition-colors text-foreground">
            <Filter size={14} />
            Filter
          </button>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead>
              <tr className="bg-muted/40">
                {["Kode", "NIS/NIP", "Nama", "Kelas", "Jenis", "Status", "Bergabung", ""].map((h) => (
                  <th key={h} className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground whitespace-nowrap">
                    {h}
                  </th>
                ))}
              </tr>
            </thead>
            <tbody>
              {filtered.map((row, i) => (
                <tr key={i} className="border-t border-border hover:bg-muted/20 transition-colors">
                  <td className="px-4 py-3 font-mono text-xs text-muted-foreground">{row.kode}</td>
                  <td className="px-4 py-3 font-mono text-xs text-muted-foreground">{row.nis}</td>
                  <td className="px-4 py-3 font-medium text-xs text-foreground">{row.nama}</td>
                  <td className="px-4 py-3 text-xs text-muted-foreground">{row.kelas}</td>
                  <td className="px-4 py-3"><StatusBadge status={row.jenis} /></td>
                  <td className="px-4 py-3"><StatusBadge status={row.status} /></td>
                  <td className="px-4 py-3 text-xs text-muted-foreground hidden lg:table-cell">{row.bergabung}</td>
                  <td className="px-4 py-3">
                    <button className="text-muted-foreground hover:text-primary transition-colors">
                      <Eye size={14} />
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        <div className="flex items-center justify-between px-4 py-3 border-t border-border text-xs text-muted-foreground">
          <span>Menampilkan {filtered.length} dari 487 anggota</span>
          <div className="flex items-center gap-1">
            {[1, 2, 3, "...", 10].map((p, i) => (
              <button
                key={i}
                className={`w-7 h-7 rounded text-xs font-medium transition-colors ${p === 1 ? "bg-primary text-primary-foreground" : "hover:bg-muted"}`}
              >
                {p}
              </button>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}

function PeminjamanPage() {
  return (
    <div className="space-y-5">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
            Peminjaman
          </h1>
          <p className="text-sm text-muted-foreground mt-0.5">73 aktif · 3 terlambat</p>
        </div>
        <button className="flex items-center gap-2 px-3 py-2 text-sm bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity">
          <Plus size={14} />
          Catat Peminjaman
        </button>
      </div>

      {/* Late alert */}
      <div className="bg-red-50 border border-red-200 rounded-lg px-4 py-3 flex items-start gap-3">
        <AlertTriangle size={16} className="shrink-0 text-red-600 mt-0.5" />
        <div>
          <p className="text-sm font-medium text-red-800">3 Peminjaman Terlambat</p>
          <p className="text-xs text-red-600 mt-0.5">Segera proses pengembalian atau perpanjangan untuk buku-buku berikut.</p>
        </div>
      </div>

      <div className="bg-card border border-border rounded-lg overflow-hidden">
        <div className="flex items-center gap-3 px-4 py-3 border-b border-border">
          <div className="flex items-center gap-2 flex-1 bg-input-background border border-border rounded-lg px-3 py-2">
            <Search size={14} className="text-muted-foreground" />
            <input
              placeholder="Cari kode transaksi, anggota..."
              className="bg-transparent text-sm outline-none flex-1 text-foreground placeholder:text-muted-foreground"
            />
          </div>
          <div className="flex items-center gap-1">
            {["Semua", "Dipinjam", "Terlambat", "Dikembalikan"].map((f) => (
              <button
                key={f}
                className={`px-3 py-1.5 text-xs rounded-lg font-medium transition-colors ${
                  f === "Semua" ? "bg-primary text-primary-foreground" : "bg-card border border-border text-muted-foreground hover:bg-secondary"
                }`}
              >
                {f}
              </button>
            ))}
          </div>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead>
              <tr className="bg-muted/40">
                {["Kode Transaksi", "Anggota", "Buku", "Tgl Pinjam", "Jatuh Tempo", "Status", ""].map((h) => (
                  <th key={h} className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground">
                    {h}
                  </th>
                ))}
              </tr>
            </thead>
            <tbody>
              {recentBorrowings.map((row, i) => (
                <tr key={i} className="border-t border-border hover:bg-muted/20 transition-colors">
                  <td className="px-4 py-3 font-mono text-xs text-muted-foreground">{row.kode}</td>
                  <td className="px-4 py-3">
                    <div className="font-medium text-xs text-foreground">{row.anggota}</div>
                    <div className="text-xs text-muted-foreground">{row.kelas}</div>
                  </td>
                  <td className="px-4 py-3 text-xs text-foreground max-w-[200px] truncate">{row.buku}</td>
                  <td className="px-4 py-3 text-xs text-muted-foreground">{row.tgl}</td>
                  <td className="px-4 py-3 text-xs">
                    <span className={`flex items-center gap-1 ${row.status === "Terlambat" ? "text-red-600 font-medium" : "text-muted-foreground"}`}>
                      {row.status === "Terlambat" && <Clock size={11} />}
                      {row.jatuh}
                    </span>
                  </td>
                  <td className="px-4 py-3"><StatusBadge status={row.status} /></td>
                  <td className="px-4 py-3">
                    <button className="flex items-center gap-1 text-xs text-primary font-medium hover:underline">
                      <CornerDownLeft size={12} />
                      Proses
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}

// ── Additional data ───────────────────────────────────────────────────────────
const activeLoans = [
  { kode: "BRW-20260702-0018", anggota: "Ahmad Fauzi", kelas: "VIII-B", buku: "Tafsir Al-Muyassar", inv: "INV00312", tglPinjam: "02 Jul 2026", jatuh: "09 Jul 2026", hari: 0, denda: 0, status: "Dipinjam" },
  { kode: "BRW-20260702-0017", anggota: "Siti Nurhaliza", kelas: "VII-A", buku: "IPA Terpadu Kelas 7", inv: "INV00311", tglPinjam: "02 Jul 2026", jatuh: "09 Jul 2026", hari: 0, denda: 0, status: "Dipinjam" },
  { kode: "BRW-20260701-0016", anggota: "Budi Santoso", kelas: "IX-C", buku: "Bahasa Indonesia Kelas 9", inv: "INV00290", tglPinjam: "01 Jul 2026", jatuh: "08 Jul 2026", hari: 2, denda: 2000, status: "Terlambat" },
  { kode: "BRW-20260701-0015", anggota: "Dewi Rahayu", kelas: "VIII-A", buku: "Matematika Kelas 8", inv: "INV00309", tglPinjam: "01 Jul 2026", jatuh: "08 Jul 2026", hari: 0, denda: 0, status: "Dipinjam" },
  { kode: "BRW-20260630-0014", anggota: "Rizki Hidayat", kelas: "VII-C", buku: "Atlas Indonesia", inv: "INV00245", tglPinjam: "30 Jun 2026", jatuh: "07 Jul 2026", hari: 5, denda: 5000, status: "Terlambat" },
  { kode: "BRW-20260628-0012", anggota: "Faisal Rahman", kelas: "IX-B", buku: "Ensiklopedi Sains Islam", inv: "INV00310", tglPinjam: "28 Jun 2026", jatuh: "05 Jul 2026", hari: 7, denda: 7000, status: "Terlambat" },
];

const returnHistory = [
  { kode: "BRW-20260630-0013", anggota: "Nur Aisyah", buku: "PKn Kelas 9", tglKembali: "02 Jul 2026", denda: 0, kondisi: "Baik", petugas: "Admin" },
  { kode: "BRW-20260625-0010", anggota: "Halimah Sadiyah", buku: "Fiqih Kelas 8", tglKembali: "01 Jul 2026", denda: 1000, kondisi: "Baik", petugas: "Admin" },
  { kode: "BRW-20260620-0008", anggota: "Drs. Hendra Kusuma", buku: "Tafsir Al-Azhar", tglKembali: "30 Jun 2026", denda: 0, kondisi: "Baik", petugas: "Admin" },
  { kode: "BRW-20260618-0007", anggota: "Irfan Maulana", buku: "IPS Terpadu Kelas 7", tglKembali: "29 Jun 2026", denda: 3000, kondisi: "Rusak Ringan", petugas: "Admin" },
];

const laporanBulanan = [
  { bulan: "Jan", dipinjam: 120, dikembalikan: 108, pengunjung: 380, denda: 45000 },
  { bulan: "Feb", dipinjam: 145, dikembalikan: 130, pengunjung: 420, denda: 62000 },
  { bulan: "Mar", dipinjam: 162, dikembalikan: 150, pengunjung: 465, denda: 38000 },
  { bulan: "Apr", dipinjam: 138, dikembalikan: 145, pengunjung: 390, denda: 55000 },
  { bulan: "Mei", dipinjam: 175, dikembalikan: 168, pengunjung: 510, denda: 72000 },
  { bulan: "Jun", dipinjam: 190, dikembalikan: 172, pengunjung: 540, denda: 84000 },
  { bulan: "Jul", dipinjam: 84, dikembalikan: 79, pengunjung: 235, denda: 21000 },
];

const topBorrowedBooks = [
  { judul: "Al-Quran dan Terjemahnya", kategori: "Agama", total: 48 },
  { judul: "IPA Terpadu SMP Kelas 8", kategori: "IPA", total: 37 },
  { judul: "Matematika SMP Kelas 9", kategori: "Matematika", total: 32 },
  { judul: "Tafsir Al-Muyassar Jilid 1", kategori: "Agama", total: 29 },
  { judul: "Bahasa Indonesia Kelas 7", kategori: "Bahasa", total: 27 },
  { judul: "Ensiklopedi Sains Islam", kategori: "Agama", total: 24 },
  { judul: "Atlas Indonesia", kategori: "IPS", total: 21 },
];

const topMembers = [
  { nama: "Ahmad Fauzi Ramadhan", kelas: "VIII-B", total: 14 },
  { nama: "Siti Nurhaliza Putri", kelas: "VII-A", total: 12 },
  { nama: "Nining Rahayu, S.Pd", kelas: "Guru", total: 11 },
  { nama: "Muhammad Rizki Hidayat", kelas: "VII-C", total: 10 },
  { nama: "Drs. Hendra Kusuma", kelas: "Guru", total: 9 },
];

// ── PengembalianPage ──────────────────────────────────────────────────────────
function PengembalianPage() {
  const [tab, setTab] = useState<"proses" | "riwayat">("proses");
  const [search, setSearch] = useState("");
  const [selected, setSelected] = useState<string | null>(null);

  const filtered = activeLoans.filter(
    (l) =>
      l.anggota.toLowerCase().includes(search.toLowerCase()) ||
      l.kode.toLowerCase().includes(search.toLowerCase()) ||
      l.buku.toLowerCase().includes(search.toLowerCase())
  );

  const selectedLoan = activeLoans.find((l) => l.kode === selected);

  return (
    <div className="space-y-5">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
            Pengembalian Buku
          </h1>
          <p className="text-sm text-muted-foreground mt-0.5">
            {activeLoans.length} aktif · {activeLoans.filter((l) => l.status === "Terlambat").length} terlambat
          </p>
        </div>
      </div>

      {/* Tabs */}
      <div className="flex gap-1 bg-muted rounded-lg p-1 w-fit">
        {(["proses", "riwayat"] as const).map((t) => (
          <button
            key={t}
            onClick={() => setTab(t)}
            className={`px-4 py-1.5 rounded-md text-xs font-medium transition-colors capitalize ${
              tab === t ? "bg-card text-foreground shadow-sm" : "text-muted-foreground hover:text-foreground"
            }`}
          >
            {t === "proses" ? "Proses Pengembalian" : "Riwayat Pengembalian"}
          </button>
        ))}
      </div>

      {tab === "proses" && (
        <div className="grid grid-cols-1 gap-4 lg:grid-cols-5">
          {/* Left: list */}
          <div className="lg:col-span-3 bg-card border border-border rounded-lg overflow-hidden">
            <div className="flex items-center gap-3 px-4 py-3 border-b border-border">
              <div className="flex items-center gap-2 flex-1 bg-input-background border border-border rounded-lg px-3 py-2">
                <Search size={14} className="text-muted-foreground" />
                <input
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                  placeholder="Cari anggota, kode, judul buku..."
                  className="bg-transparent text-sm outline-none flex-1 text-foreground placeholder:text-muted-foreground"
                />
              </div>
            </div>
            <div className="divide-y divide-border">
              {filtered.map((loan) => (
                <button
                  key={loan.kode}
                  onClick={() => setSelected(loan.kode === selected ? null : loan.kode)}
                  className={`w-full text-left px-4 py-3 flex items-start gap-3 hover:bg-muted/20 transition-colors ${
                    selected === loan.kode ? "bg-secondary/60" : ""
                  }`}
                >
                  <div
                    className={`mt-0.5 w-2 h-2 rounded-full shrink-0 ${
                      loan.status === "Terlambat" ? "bg-red-500" : "bg-blue-400"
                    }`}
                  />
                  <div className="flex-1 min-w-0">
                    <div className="flex items-center justify-between gap-2">
                      <span className="font-medium text-xs text-foreground">{loan.anggota}</span>
                      <StatusBadge status={loan.status} />
                    </div>
                    <div className="text-xs text-muted-foreground truncate mt-0.5">{loan.buku}</div>
                    <div className="flex items-center gap-3 mt-1 text-[10px] text-muted-foreground">
                      <span className="font-mono">{loan.kode}</span>
                      <span>·</span>
                      <span className={loan.status === "Terlambat" ? "text-red-500 font-medium" : ""}>
                        {loan.status === "Terlambat" ? `${loan.hari} hari terlambat` : `Jatuh tempo ${loan.jatuh}`}
                      </span>
                    </div>
                  </div>
                </button>
              ))}
            </div>
          </div>

          {/* Right: detail form */}
          <div className="lg:col-span-2">
            {selectedLoan ? (
              <div className="bg-card border border-border rounded-lg overflow-hidden sticky top-0">
                <div className="px-4 py-3 border-b border-border bg-secondary/30">
                  <h3 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
                    Detail Pengembalian
                  </h3>
                </div>
                <div className="p-4 space-y-4">
                  <div className="space-y-2">
                    {[
                      { label: "Kode Transaksi", val: selectedLoan.kode },
                      { label: "Anggota", val: `${selectedLoan.anggota} · ${selectedLoan.kelas}` },
                      { label: "Judul Buku", val: selectedLoan.buku },
                      { label: "Kode Inv.", val: selectedLoan.inv },
                      { label: "Tgl Pinjam", val: selectedLoan.tglPinjam },
                      { label: "Jatuh Tempo", val: selectedLoan.jatuh },
                    ].map((r) => (
                      <div key={r.label} className="flex justify-between gap-2 text-xs">
                        <span className="text-muted-foreground shrink-0">{r.label}</span>
                        <span className="text-foreground font-medium text-right">{r.val}</span>
                      </div>
                    ))}
                  </div>

                  {selectedLoan.status === "Terlambat" && (
                    <div className="bg-red-50 border border-red-200 rounded-lg p-3">
                      <div className="flex items-center gap-2 text-red-700 text-xs font-medium mb-1">
                        <AlertTriangle size={13} />
                        Terlambat {selectedLoan.hari} hari
                      </div>
                      <div className="flex justify-between text-xs">
                        <span className="text-red-600">Denda (Rp 1.000/hari)</span>
                        <span className="font-bold text-red-700">Rp {selectedLoan.denda.toLocaleString("id-ID")}</span>
                      </div>
                    </div>
                  )}

                  <div className="space-y-2">
                    <label className="text-xs font-medium text-foreground">Kondisi Saat Kembali</label>
                    <select className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring">
                      <option>Baik</option>
                      <option>Rusak Ringan</option>
                      <option>Rusak Berat</option>
                      <option>Hilang</option>
                    </select>
                  </div>

                  <div className="space-y-2">
                    <label className="text-xs font-medium text-foreground">Catatan</label>
                    <textarea
                      rows={2}
                      placeholder="Catatan tambahan (opsional)..."
                      className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring resize-none placeholder:text-muted-foreground"
                    />
                  </div>

                  <button className="w-full flex items-center justify-center gap-2 py-2.5 bg-primary text-primary-foreground rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">
                    <CheckCircle2 size={15} />
                    Proses Pengembalian
                  </button>
                </div>
              </div>
            ) : (
              <div className="bg-card border border-border rounded-lg p-8 flex flex-col items-center justify-center text-center h-full min-h-[300px]">
                <CornerDownLeft size={32} className="text-muted-foreground/30 mb-3" />
                <p className="text-sm text-muted-foreground">Pilih peminjaman dari daftar kiri untuk memproses pengembalian</p>
              </div>
            )}
          </div>
        </div>
      )}

      {tab === "riwayat" && (
        <div className="bg-card border border-border rounded-lg overflow-hidden">
          <div className="flex items-center justify-between px-4 py-3 border-b border-border">
            <span className="text-sm font-medium text-foreground">Riwayat Pengembalian Bulan Ini</span>
            <button className="flex items-center gap-1.5 text-xs text-muted-foreground hover:text-foreground border border-border rounded-lg px-3 py-1.5 bg-card hover:bg-secondary transition-colors">
              <Download size={12} />
              Ekspor Excel
            </button>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead>
                <tr className="bg-muted/40">
                  {["Kode Transaksi", "Anggota", "Judul Buku", "Tgl Kembali", "Kondisi", "Denda", "Petugas"].map((h) => (
                    <th key={h} className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground whitespace-nowrap">
                      {h}
                    </th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {returnHistory.map((row, i) => (
                  <tr key={i} className="border-t border-border hover:bg-muted/20 transition-colors">
                    <td className="px-4 py-3 font-mono text-xs text-muted-foreground">{row.kode}</td>
                    <td className="px-4 py-3 text-xs font-medium text-foreground">{row.anggota}</td>
                    <td className="px-4 py-3 text-xs text-foreground max-w-[180px] truncate">{row.buku}</td>
                    <td className="px-4 py-3 text-xs text-muted-foreground">{row.tglKembali}</td>
                    <td className="px-4 py-3"><StatusBadge status={row.kondisi} /></td>
                    <td className="px-4 py-3 text-xs">
                      {row.denda > 0 ? (
                        <span className="text-red-600 font-medium">Rp {row.denda.toLocaleString("id-ID")}</span>
                      ) : (
                        <span className="text-emerald-600">—</span>
                      )}
                    </td>
                    <td className="px-4 py-3 text-xs text-muted-foreground">{row.petugas}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}
    </div>
  );
}

// ── LaporanPage ───────────────────────────────────────────────────────────────
function LaporanPage() {
  const [tab, setTab] = useState<"ringkasan" | "buku" | "anggota" | "denda">("ringkasan");
  const [periode, setPeriode] = useState("2026");

  const totalPinjam = laporanBulanan.reduce((s, r) => s + r.dipinjam, 0);
  const totalKembali = laporanBulanan.reduce((s, r) => s + r.dikembalikan, 0);
  const totalKunjungan = laporanBulanan.reduce((s, r) => s + r.pengunjung, 0);
  const totalDenda = laporanBulanan.reduce((s, r) => s + r.denda, 0);

  return (
    <div className="space-y-5">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
            Laporan Perpustakaan
          </h1>
          <p className="text-sm text-muted-foreground mt-0.5">Data rekapitulasi aktivitas perpustakaan</p>
        </div>
        <div className="flex items-center gap-2">
          <select
            value={periode}
            onChange={(e) => setPeriode(e.target.value)}
            className="text-xs px-3 py-2 bg-card border border-border rounded-lg text-foreground outline-none"
          >
            {["2026", "2025", "2024"].map((y) => (
              <option key={y}>{y}</option>
            ))}
          </select>
          <button className="flex items-center gap-2 px-3 py-2 text-sm bg-card border border-border rounded-lg hover:bg-secondary transition-colors text-foreground">
            <Download size={14} />
            Ekspor PDF
          </button>
          <button className="flex items-center gap-2 px-3 py-2 text-sm bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity">
            <Download size={14} />
            Ekspor Excel
          </button>
        </div>
      </div>

      {/* Summary cards */}
      <div className="grid grid-cols-2 gap-3 lg:grid-cols-4">
        {[
          { label: "Total Peminjaman", val: totalPinjam.toLocaleString("id-ID"), icon: <ArrowLeftRight size={16} />, color: "bg-[#145048]" },
          { label: "Total Pengembalian", val: totalKembali.toLocaleString("id-ID"), icon: <CornerDownLeft size={16} />, color: "bg-[#2a8a76]" },
          { label: "Total Kunjungan", val: totalKunjungan.toLocaleString("id-ID"), icon: <UserCheck size={16} />, color: "bg-[#5ba89a]" },
          { label: "Total Denda", val: `Rp ${totalDenda.toLocaleString("id-ID")}`, icon: <AlertCircle size={16} />, color: "bg-[#bf7c33]" },
        ].map((c) => (
          <div key={c.label} className="bg-card border border-border rounded-lg p-4 flex items-center gap-3">
            <div className={`w-9 h-9 rounded-lg flex items-center justify-center text-white shrink-0 ${c.color}`}>
              {c.icon}
            </div>
            <div>
              <div className="text-lg font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>{c.val}</div>
              <div className="text-xs text-muted-foreground">{c.label}</div>
            </div>
          </div>
        ))}
      </div>

      {/* Tabs */}
      <div className="flex gap-1 bg-muted rounded-lg p-1 w-fit">
        {(["ringkasan", "buku", "anggota", "denda"] as const).map((t) => (
          <button
            key={t}
            onClick={() => setTab(t)}
            className={`px-4 py-1.5 rounded-md text-xs font-medium transition-colors ${
              tab === t ? "bg-card text-foreground shadow-sm" : "text-muted-foreground hover:text-foreground"
            }`}
          >
            {t === "ringkasan" ? "Ringkasan" : t === "buku" ? "Buku Terpopuler" : t === "anggota" ? "Anggota Aktif" : "Denda"}
          </button>
        ))}
      </div>

      {tab === "ringkasan" && (
        <div className="space-y-4">
          <div className="bg-card border border-border rounded-lg p-4">
            <div className="flex items-center justify-between mb-4">
              <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
                Tren Bulanan {periode}
              </h2>
              <div className="flex items-center gap-4 text-xs text-muted-foreground">
                <span className="flex items-center gap-1.5"><span className="w-2.5 h-2.5 rounded-sm bg-[#145048] inline-block" /> Dipinjam</span>
                <span className="flex items-center gap-1.5"><span className="w-2.5 h-2.5 rounded-sm bg-[#5ba89a] inline-block" /> Dikembalikan</span>
                <span className="flex items-center gap-1.5"><span className="w-2.5 h-2.5 rounded-sm bg-[#bf7c33] inline-block" /> Kunjungan</span>
              </div>
            </div>
            <ResponsiveContainer width="100%" height={220}>
              <BarChart data={laporanBulanan} barGap={3}>
                <CartesianGrid strokeDasharray="3 3" stroke="rgba(0,0,0,0.06)" vertical={false} />
                <XAxis dataKey="bulan" tick={{ fontSize: 11, fill: "#6b7a77" }} axisLine={false} tickLine={false} />
                <YAxis tick={{ fontSize: 11, fill: "#6b7a77" }} axisLine={false} tickLine={false} />
                <Tooltip contentStyle={{ borderRadius: "6px", border: "1px solid rgba(0,0,0,0.08)", fontSize: 12 }} cursor={{ fill: "rgba(0,0,0,0.03)" }} />
                <Bar key="lap-pinjam" dataKey="dipinjam" name="Dipinjam" fill="#145048" radius={[3, 3, 0, 0]} />
                <Bar key="lap-kembali" dataKey="dikembalikan" name="Dikembalikan" fill="#5ba89a" radius={[3, 3, 0, 0]} />
                <Bar key="lap-kunjungan" dataKey="pengunjung" name="Kunjungan" fill="#bf7c33" radius={[3, 3, 0, 0]} />
              </BarChart>
            </ResponsiveContainer>
          </div>

          <div className="bg-card border border-border rounded-lg overflow-hidden">
            <div className="px-4 py-3 border-b border-border">
              <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
                Tabel Rekapitulasi Bulanan
              </h2>
            </div>
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="bg-muted/40">
                    {["Bulan", "Peminjaman", "Pengembalian", "Kunjungan", "Denda (Rp)"].map((h) => (
                      <th key={h} className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground">{h}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {laporanBulanan.map((row, i) => (
                    <tr key={i} className="border-t border-border hover:bg-muted/20 transition-colors">
                      <td className="px-4 py-3 text-xs font-medium text-foreground">{row.bulan} {periode}</td>
                      <td className="px-4 py-3 text-xs text-foreground font-mono">{row.dipinjam}</td>
                      <td className="px-4 py-3 text-xs text-foreground font-mono">{row.dikembalikan}</td>
                      <td className="px-4 py-3 text-xs text-foreground font-mono">{row.pengunjung}</td>
                      <td className="px-4 py-3 text-xs font-mono">{row.denda > 0 ? <span className="text-amber-700">{row.denda.toLocaleString("id-ID")}</span> : "—"}</td>
                    </tr>
                  ))}
                  <tr className="border-t-2 border-border bg-secondary/30">
                    <td className="px-4 py-3 text-xs font-bold text-foreground">TOTAL</td>
                    <td className="px-4 py-3 text-xs font-bold text-foreground font-mono">{totalPinjam}</td>
                    <td className="px-4 py-3 text-xs font-bold text-foreground font-mono">{totalKembali}</td>
                    <td className="px-4 py-3 text-xs font-bold text-foreground font-mono">{totalKunjungan}</td>
                    <td className="px-4 py-3 text-xs font-bold text-amber-700 font-mono">{totalDenda.toLocaleString("id-ID")}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}

      {tab === "buku" && (
        <div className="bg-card border border-border rounded-lg overflow-hidden">
          <div className="flex items-center justify-between px-4 py-3 border-b border-border">
            <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
              Buku Paling Banyak Dipinjam
            </h2>
            <span className="text-xs text-muted-foreground">Periode {periode}</span>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead>
                <tr className="bg-muted/40">
                  {["#", "Judul Buku", "Kategori", "Total Pinjam", "Grafik"].map((h) => (
                    <th key={h} className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground">{h}</th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {topBorrowedBooks.map((row, i) => (
                  <tr key={i} className="border-t border-border hover:bg-muted/20 transition-colors">
                    <td className="px-4 py-3 text-xs font-bold text-muted-foreground w-8">{i + 1}</td>
                    <td className="px-4 py-3 text-xs font-medium text-foreground">{row.judul}</td>
                    <td className="px-4 py-3 text-xs text-muted-foreground">{row.kategori}</td>
                    <td className="px-4 py-3 text-xs font-bold text-foreground font-mono">{row.total}x</td>
                    <td className="px-4 py-3 w-32">
                      <div className="h-2 bg-muted rounded-full overflow-hidden">
                        <div
                          className="h-full rounded-full bg-[#145048] transition-all"
                          style={{ width: `${(row.total / topBorrowedBooks[0].total) * 100}%` }}
                        />
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {tab === "anggota" && (
        <div className="bg-card border border-border rounded-lg overflow-hidden">
          <div className="flex items-center justify-between px-4 py-3 border-b border-border">
            <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
              Anggota Paling Aktif Meminjam
            </h2>
            <span className="text-xs text-muted-foreground">Periode {periode}</span>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead>
                <tr className="bg-muted/40">
                  {["#", "Nama Anggota", "Kelas/Jabatan", "Total Pinjam", "Grafik"].map((h) => (
                    <th key={h} className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground">{h}</th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {topMembers.map((row, i) => (
                  <tr key={i} className="border-t border-border hover:bg-muted/20 transition-colors">
                    <td className="px-4 py-3 text-xs font-bold text-muted-foreground w-8">{i + 1}</td>
                    <td className="px-4 py-3 text-xs font-medium text-foreground">{row.nama}</td>
                    <td className="px-4 py-3 text-xs text-muted-foreground">{row.kelas}</td>
                    <td className="px-4 py-3 text-xs font-bold text-foreground font-mono">{row.total}x</td>
                    <td className="px-4 py-3 w-32">
                      <div className="h-2 bg-muted rounded-full overflow-hidden">
                        <div
                          className="h-full rounded-full bg-[#2a8a76] transition-all"
                          style={{ width: `${(row.total / topMembers[0].total) * 100}%` }}
                        />
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {tab === "denda" && (
        <div className="space-y-4">
          <div className="grid grid-cols-3 gap-3">
            {[
              { label: "Total Denda Dikenakan", val: `Rp ${totalDenda.toLocaleString("id-ID")}`, color: "text-red-600" },
              { label: "Sudah Dibayar", val: `Rp ${Math.round(totalDenda * 0.72).toLocaleString("id-ID")}`, color: "text-emerald-600" },
              { label: "Belum Dibayar", val: `Rp ${Math.round(totalDenda * 0.28).toLocaleString("id-ID")}`, color: "text-amber-600" },
            ].map((c) => (
              <div key={c.label} className="bg-card border border-border rounded-lg p-4">
                <div className={`text-xl font-bold ${c.color}`} style={{ fontFamily: "'Roboto Slab', serif" }}>{c.val}</div>
                <div className="text-xs text-muted-foreground mt-0.5">{c.label}</div>
              </div>
            ))}
          </div>
          <div className="bg-card border border-border rounded-lg p-4">
            <h2 className="text-sm font-semibold text-foreground mb-4" style={{ fontFamily: "'Roboto Slab', serif" }}>
              Denda per Bulan (Rp)
            </h2>
            <ResponsiveContainer width="100%" height={200}>
              <AreaChart data={laporanBulanan}>
                <defs>
                  <linearGradient id="dendaGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="5%" stopColor="#bf7c33" stopOpacity={0.2} />
                    <stop offset="95%" stopColor="#bf7c33" stopOpacity={0} />
                  </linearGradient>
                </defs>
                <CartesianGrid strokeDasharray="3 3" stroke="rgba(0,0,0,0.06)" vertical={false} />
                <XAxis dataKey="bulan" tick={{ fontSize: 11, fill: "#6b7a77" }} axisLine={false} tickLine={false} />
                <YAxis tick={{ fontSize: 11, fill: "#6b7a77" }} axisLine={false} tickLine={false} tickFormatter={(v) => `${(v / 1000).toFixed(0)}k`} />
                <Tooltip contentStyle={{ borderRadius: "6px", border: "1px solid rgba(0,0,0,0.08)", fontSize: 12 }} formatter={(v: number) => [`Rp ${v.toLocaleString("id-ID")}`, "Denda"]} />
                <Area key="denda-area" type="monotone" dataKey="denda" name="Denda" stroke="#bf7c33" strokeWidth={2} fill="url(#dendaGrad)" dot={{ r: 3, fill: "#bf7c33" }} />
              </AreaChart>
            </ResponsiveContainer>
          </div>
        </div>
      )}
    </div>
  );
}

// ── PengaturanPage ────────────────────────────────────────────────────────────
function PengaturanPage() {
  const [tab, setTab] = useState<"umum" | "sirkulasi" | "pengguna" | "sistem">("umum");
  const [saved, setSaved] = useState(false);

  const handleSave = () => {
    setSaved(true);
    setTimeout(() => setSaved(false), 2500);
  };

  const users = [
    { nama: "Admin Perpustakaan", email: "admin@alihsan.sch.id", role: "Administrator", lastLogin: "02 Jul 2026 08:14", status: "Aktif" },
    { nama: "Nining Rahayu, S.Pd", email: "nining@alihsan.sch.id", role: "Pustakawan", lastLogin: "02 Jul 2026 07:55", status: "Aktif" },
    { nama: "Drs. Hendra Kusuma", email: "hendra@alihsan.sch.id", role: "Kepala Perpustakaan", lastLogin: "01 Jul 2026 14:30", status: "Aktif" },
    { nama: "Kepala Sekolah", email: "kepsek@alihsan.sch.id", role: "Kepala Sekolah (R/O)", lastLogin: "28 Jun 2026 10:00", status: "Aktif" },
  ];

  return (
    <div className="space-y-5">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
            Pengaturan Sistem
          </h1>
          <p className="text-sm text-muted-foreground mt-0.5">Konfigurasi perpustakaan, sirkulasi, dan pengguna</p>
        </div>
        {saved && (
          <div className="flex items-center gap-2 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2">
            <CheckCircle2 size={14} />
            Perubahan berhasil disimpan
          </div>
        )}
      </div>

      {/* Tabs */}
      <div className="flex gap-1 bg-muted rounded-lg p-1 w-fit">
        {(["umum", "sirkulasi", "pengguna", "sistem"] as const).map((t) => (
          <button
            key={t}
            onClick={() => setTab(t)}
            className={`px-4 py-1.5 rounded-md text-xs font-medium transition-colors ${
              tab === t ? "bg-card text-foreground shadow-sm" : "text-muted-foreground hover:text-foreground"
            }`}
          >
            {t === "umum" ? "Umum" : t === "sirkulasi" ? "Sirkulasi" : t === "pengguna" ? "Pengguna" : "Sistem"}
          </button>
        ))}
      </div>

      {tab === "umum" && (
        <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
          <div className="bg-card border border-border rounded-lg overflow-hidden">
            <div className="px-4 py-3 border-b border-border bg-secondary/20">
              <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>Identitas Perpustakaan</h2>
            </div>
            <div className="p-4 space-y-4">
              {[
                { label: "Nama Perpustakaan", val: "Perpustakaan MTs Al-Ihsan Batujajar", type: "text" },
                { label: "Nama Sekolah", val: "MTs Al-Ihsan Batujajar", type: "text" },
                { label: "Alamat", val: "Jl. Raya Batujajar No. 12, Bandung Barat", type: "text" },
                { label: "Telepon", val: "(022) 6867890", type: "text" },
                { label: "Email", val: "perpustakaan@alihsan.sch.id", type: "email" },
                { label: "NPSN", val: "20269312", type: "text" },
              ].map((f) => (
                <div key={f.label} className="space-y-1">
                  <label className="text-xs font-medium text-foreground">{f.label}</label>
                  <input
                    type={f.type}
                    defaultValue={f.val}
                    className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring"
                  />
                </div>
              ))}
              <button onClick={handleSave} className="flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-lg text-xs font-medium hover:opacity-90 transition-opacity">
                <CheckCircle2 size={13} />
                Simpan Perubahan
              </button>
            </div>
          </div>

          <div className="space-y-4">
            <div className="bg-card border border-border rounded-lg overflow-hidden">
              <div className="px-4 py-3 border-b border-border bg-secondary/20">
                <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>Tahun Ajaran Aktif</h2>
              </div>
              <div className="p-4 space-y-3">
                <div className="space-y-1">
                  <label className="text-xs font-medium text-foreground">Tahun Ajaran</label>
                  <select className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring">
                    <option selected>2025/2026</option>
                    <option>2024/2025</option>
                  </select>
                </div>
                <div className="space-y-1">
                  <label className="text-xs font-medium text-foreground">Semester Aktif</label>
                  <select className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring">
                    <option>Genap (Jan — Jun 2026)</option>
                    <option selected>Ganjil (Jul — Des 2026)</option>
                  </select>
                </div>
                <button onClick={handleSave} className="flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-lg text-xs font-medium hover:opacity-90 transition-opacity">
                  <CheckCircle2 size={13} />
                  Simpan
                </button>
              </div>
            </div>

            <div className="bg-card border border-border rounded-lg overflow-hidden">
              <div className="px-4 py-3 border-b border-border bg-secondary/20">
                <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>Format Kode Otomatis</h2>
              </div>
              <div className="p-4 space-y-3">
                {[
                  { label: "Format Kode Transaksi", val: "BRW-{YYYYMMDD}-{####}" },
                  { label: "Format Kode Inventaris", val: "INV{#####}" },
                  { label: "Format Kode Anggota", val: "MBR-{YYYY}-{####}" },
                ].map((f) => (
                  <div key={f.label} className="space-y-1">
                    <label className="text-xs font-medium text-foreground">{f.label}</label>
                    <input
                      defaultValue={f.val}
                      className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground font-mono outline-none focus:ring-2 focus:ring-ring"
                    />
                  </div>
                ))}
                <button onClick={handleSave} className="flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-lg text-xs font-medium hover:opacity-90 transition-opacity">
                  <CheckCircle2 size={13} />
                  Simpan
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

      {tab === "sirkulasi" && (
        <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
          <div className="bg-card border border-border rounded-lg overflow-hidden">
            <div className="px-4 py-3 border-b border-border bg-secondary/20">
              <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>Aturan Peminjaman per Jenis Anggota</h2>
            </div>
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="bg-muted/40">
                    {["Jenis Anggota", "Maks. Buku", "Durasi (hari)", "Perpanjangan"].map((h) => (
                      <th key={h} className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground">{h}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {[
                    { jenis: "Siswa", maks: 2, durasi: 7, perpanjangan: 1 },
                    { jenis: "Guru", maks: 5, durasi: 14, perpanjangan: 2 },
                    { jenis: "Staff", maks: 3, durasi: 14, perpanjangan: 1 },
                  ].map((row, i) => (
                    <tr key={i} className="border-t border-border">
                      <td className="px-4 py-3 text-xs font-medium text-foreground">{row.jenis}</td>
                      <td className="px-4 py-2">
                        <input type="number" defaultValue={row.maks} min={1} max={10}
                          className="w-16 text-xs px-2 py-1.5 bg-input-background border border-border rounded text-center outline-none focus:ring-2 focus:ring-ring" />
                      </td>
                      <td className="px-4 py-2">
                        <input type="number" defaultValue={row.durasi} min={1} max={30}
                          className="w-16 text-xs px-2 py-1.5 bg-input-background border border-border rounded text-center outline-none focus:ring-2 focus:ring-ring" />
                      </td>
                      <td className="px-4 py-2">
                        <input type="number" defaultValue={row.perpanjangan} min={0} max={3}
                          className="w-16 text-xs px-2 py-1.5 bg-input-background border border-border rounded text-center outline-none focus:ring-2 focus:ring-ring" />
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
            <div className="px-4 py-3 border-t border-border">
              <button onClick={handleSave} className="flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-lg text-xs font-medium hover:opacity-90 transition-opacity">
                <CheckCircle2 size={13} />
                Simpan Aturan
              </button>
            </div>
          </div>

          <div className="space-y-4">
            <div className="bg-card border border-border rounded-lg overflow-hidden">
              <div className="px-4 py-3 border-b border-border bg-secondary/20">
                <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>Pengaturan Denda</h2>
              </div>
              <div className="p-4 space-y-3">
                {[
                  { label: "Denda Keterlambatan (per hari)", val: "1000", prefix: "Rp" },
                  { label: "Denda Kerusakan Ringan", val: "10000", prefix: "Rp" },
                  { label: "Denda Kerusakan Berat", val: "50000", prefix: "Rp" },
                  { label: "Denda Kehilangan", val: "100000", prefix: "Rp" },
                ].map((f) => (
                  <div key={f.label} className="space-y-1">
                    <label className="text-xs font-medium text-foreground">{f.label}</label>
                    <div className="flex items-center border border-border rounded-lg overflow-hidden bg-input-background">
                      <span className="px-3 py-2 text-xs text-muted-foreground bg-muted border-r border-border">{f.prefix}</span>
                      <input
                        type="number"
                        defaultValue={f.val}
                        className="flex-1 px-3 py-2 text-xs bg-transparent text-foreground outline-none"
                      />
                    </div>
                  </div>
                ))}
                <button onClick={handleSave} className="flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-lg text-xs font-medium hover:opacity-90 transition-opacity">
                  <CheckCircle2 size={13} />
                  Simpan Pengaturan Denda
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

      {tab === "pengguna" && (
        <div className="space-y-4">
          <div className="bg-card border border-border rounded-lg overflow-hidden">
            <div className="flex items-center justify-between px-4 py-3 border-b border-border">
              <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>Daftar Pengguna Sistem</h2>
              <button className="flex items-center gap-2 px-3 py-1.5 text-xs bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity">
                <Plus size={12} />
                Tambah Pengguna
              </button>
            </div>
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="bg-muted/40">
                    {["Nama", "Email", "Role", "Login Terakhir", "Status", ""].map((h) => (
                      <th key={h} className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground whitespace-nowrap">{h}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {users.map((u, i) => (
                    <tr key={i} className="border-t border-border hover:bg-muted/20 transition-colors">
                      <td className="px-4 py-3">
                        <div className="flex items-center gap-2.5">
                          <div className="w-7 h-7 rounded-full bg-[#145048] flex items-center justify-center text-white text-[10px] font-bold shrink-0">
                            {u.nama.charAt(0)}
                          </div>
                          <span className="text-xs font-medium text-foreground">{u.nama}</span>
                        </div>
                      </td>
                      <td className="px-4 py-3 text-xs text-muted-foreground">{u.email}</td>
                      <td className="px-4 py-3">
                        <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-secondary text-secondary-foreground border border-border">
                          {u.role}
                        </span>
                      </td>
                      <td className="px-4 py-3 text-xs text-muted-foreground">{u.lastLogin}</td>
                      <td className="px-4 py-3"><StatusBadge status={u.status} /></td>
                      <td className="px-4 py-3">
                        <button className="text-xs text-primary font-medium hover:underline">Edit</button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>

          <div className="bg-card border border-border rounded-lg overflow-hidden">
            <div className="px-4 py-3 border-b border-border bg-secondary/20">
              <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>Role & Hak Akses</h2>
            </div>
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="bg-muted/40">
                    <th className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground">Modul</th>
                    {["Administrator", "Kepala Perpustakaan", "Pustakawan", "Kepala Sekolah"].map((r) => (
                      <th key={r} className="text-center px-3 py-2.5 text-xs font-medium text-muted-foreground whitespace-nowrap">{r}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {[
                    { modul: "Dashboard", access: [true, true, true, true] },
                    { modul: "Buku & Koleksi", access: [true, true, true, false] },
                    { modul: "Anggota", access: [true, true, true, false] },
                    { modul: "Peminjaman", access: [true, true, true, false] },
                    { modul: "Pengembalian", access: [true, true, true, false] },
                    { modul: "Laporan", access: [true, true, false, true] },
                    { modul: "Pengaturan", access: [true, false, false, false] },
                    { modul: "Manajemen Pengguna", access: [true, false, false, false] },
                  ].map((row, i) => (
                    <tr key={i} className="border-t border-border hover:bg-muted/20 transition-colors">
                      <td className="px-4 py-2.5 text-xs font-medium text-foreground">{row.modul}</td>
                      {row.access.map((a, j) => (
                        <td key={j} className="px-3 py-2.5 text-center">
                          {a ? (
                            <span className="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 text-emerald-600">
                              <CheckCircle2 size={12} />
                            </span>
                          ) : (
                            <span className="inline-flex items-center justify-center w-5 h-5 rounded-full bg-muted text-muted-foreground/30">
                              <X size={11} />
                            </span>
                          )}
                        </td>
                      ))}
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}

      {tab === "sistem" && (
        <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
          <div className="space-y-4">
            <div className="bg-card border border-border rounded-lg overflow-hidden">
              <div className="px-4 py-3 border-b border-border bg-secondary/20">
                <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>Backup Database</h2>
              </div>
              <div className="p-4 space-y-3">
                <p className="text-xs text-muted-foreground">Buat salinan cadangan seluruh data perpustakaan. Backup disimpan sebagai file SQL terenkripsi.</p>
                <div className="bg-muted/40 rounded-lg p-3 space-y-1.5 text-xs">
                  <div className="flex justify-between text-foreground">
                    <span className="text-muted-foreground">Backup terakhir</span>
                    <span className="font-medium">01 Jul 2026, 23:00</span>
                  </div>
                  <div className="flex justify-between text-foreground">
                    <span className="text-muted-foreground">Ukuran</span>
                    <span className="font-medium">4.2 MB</span>
                  </div>
                  <div className="flex justify-between text-foreground">
                    <span className="text-muted-foreground">Jadwal otomatis</span>
                    <span className="font-medium text-emerald-600">Aktif · Setiap hari 23:00</span>
                  </div>
                </div>
                <div className="flex gap-2">
                  <button className="flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-lg text-xs font-medium hover:opacity-90 transition-opacity">
                    <Download size={13} />
                    Backup Sekarang
                  </button>
                  <button className="flex items-center gap-2 px-4 py-2 bg-card border border-border rounded-lg text-xs font-medium hover:bg-secondary transition-colors text-foreground">
                    <Upload size={13} />
                    Restore
                  </button>
                </div>
              </div>
            </div>

            <div className="bg-card border border-border rounded-lg overflow-hidden">
              <div className="px-4 py-3 border-b border-border bg-secondary/20">
                <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>Informasi Sistem</h2>
              </div>
              <div className="p-4 space-y-2">
                {[
                  { label: "Versi Aplikasi", val: "SIPUS v1.0.0" },
                  { label: "Framework", val: "Laravel 12 / PHP 8.3" },
                  { label: "Database", val: "MySQL 8.0.36" },
                  { label: "Server", val: "Apache 2.4" },
                  { label: "Uptime", val: "7 hari 14 jam 22 menit" },
                  { label: "Memory Usage", val: "128 MB / 512 MB" },
                ].map((r) => (
                  <div key={r.label} className="flex justify-between text-xs py-1.5 border-b border-border last:border-0">
                    <span className="text-muted-foreground">{r.label}</span>
                    <span className="font-mono font-medium text-foreground">{r.val}</span>
                  </div>
                ))}
              </div>
            </div>
          </div>

          <div className="space-y-4">
            <div className="bg-card border border-border rounded-lg overflow-hidden">
              <div className="px-4 py-3 border-b border-border bg-secondary/20">
                <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>Log Aktivitas Terbaru</h2>
              </div>
              <div className="divide-y divide-border max-h-64 overflow-y-auto">
                {[
                  { aksi: "login", user: "Admin", waktu: "02 Jul 08:14", ket: "Login berhasil" },
                  { aksi: "create", user: "Admin", waktu: "02 Jul 08:20", ket: "Tambah buku INV00312" },
                  { aksi: "borrow", user: "Admin", waktu: "02 Jul 09:05", ket: "Peminjaman BRW-20260702-0018" },
                  { aksi: "borrow", user: "Admin", waktu: "02 Jul 09:12", ket: "Peminjaman BRW-20260702-0017" },
                  { aksi: "return", user: "Admin", waktu: "02 Jul 09:45", ket: "Pengembalian BRW-20260630-0013" },
                  { aksi: "import", user: "Nining Rahayu", waktu: "01 Jul 14:20", ket: "Import anggota 48 data" },
                  { aksi: "export", user: "Admin", waktu: "01 Jul 15:10", ket: "Ekspor laporan Juni 2026" },
                  { aksi: "update", user: "Admin", waktu: "01 Jul 15:32", ket: "Update pengaturan denda" },
                ].map((log, i) => {
                  const colorMap: Record<string, string> = {
                    login: "bg-blue-100 text-blue-600",
                    create: "bg-emerald-100 text-emerald-600",
                    borrow: "bg-purple-100 text-purple-600",
                    return: "bg-teal-100 text-teal-600",
                    import: "bg-amber-100 text-amber-600",
                    export: "bg-orange-100 text-orange-600",
                    update: "bg-gray-100 text-gray-600",
                  };
                  return (
                    <div key={i} className="flex items-start gap-3 px-4 py-2.5">
                      <span className={`mt-0.5 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase shrink-0 ${colorMap[log.aksi] ?? "bg-gray-100 text-gray-600"}`}>
                        {log.aksi}
                      </span>
                      <div className="flex-1 min-w-0">
                        <div className="text-xs text-foreground truncate">{log.ket}</div>
                        <div className="text-[10px] text-muted-foreground">{log.user} · {log.waktu}</div>
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>

            <div className="bg-amber-50 border border-amber-200 rounded-lg p-4">
              <h3 className="text-xs font-semibold text-amber-800 mb-2">Zona Berbahaya</h3>
              <p className="text-xs text-amber-700 mb-3">Tindakan berikut bersifat permanen dan tidak dapat dibatalkan.</p>
              <div className="space-y-2">
                <button className="w-full flex items-center gap-2 px-3 py-2 text-xs font-medium text-red-700 bg-white border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                  <AlertTriangle size={12} />
                  Reset Data Tahun Ajaran Lama
                </button>
                <button className="w-full flex items-center gap-2 px-3 py-2 text-xs font-medium text-red-700 bg-white border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                  <AlertTriangle size={12} />
                  Hapus Semua Log Aktivitas
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

// ── Eksemplar data ────────────────────────────────────────────────────────────
const eksemplarData = [
  { inv: "INV00312", judul: "Tafsir Al-Muyassar Jilid 2", rak: "RAK-A1", kondisi: "Baik", status: "Tersedia", sumber: "Pembelian", tglAkuisisi: "10 Jan 2024", harga: 125000 },
  { inv: "INV00311", judul: "IPA Terpadu SMP Kelas 8", rak: "RAK-B3", kondisi: "Baik", status: "Dipinjam", sumber: "Pembelian", tglAkuisisi: "15 Mar 2023", harga: 85000 },
  { inv: "INV00310", judul: "Ensiklopedi Sains Islam", rak: "RAK-A2", kondisi: "Baik", status: "Tersedia", sumber: "Hibah", tglAkuisisi: "05 Jun 2022", harga: 0 },
  { inv: "INV00309", judul: "Matematika SMP Kelas 9", rak: "RAK-C1", kondisi: "Rusak Ringan", status: "Tersedia", sumber: "Pembelian", tglAkuisisi: "20 Jul 2023", harga: 78000 },
  { inv: "INV00308", judul: "Kamus Besar Bahasa Indonesia", rak: "RAK-D2", kondisi: "Baik", status: "Tersedia", sumber: "Bantuan Pemerintah", tglAkuisisi: "01 Agt 2021", harga: 0 },
  { inv: "INV00307", judul: "Fiqih Kelas 8", rak: "RAK-A3", kondisi: "Baik", status: "Tersedia", sumber: "Pembelian", tglAkuisisi: "12 Jan 2022", harga: 65000 },
  { inv: "INV00306", judul: "IPS Terpadu Kelas 7", rak: "RAK-C2", kondisi: "Rusak Berat", status: "Tidak Aktif", sumber: "Pembelian", tglAkuisisi: "08 Feb 2020", harga: 72000 },
  { inv: "INV00305", judul: "Bahasa Arab Kelas 7", rak: "RAK-A1", kondisi: "Baik", status: "Tersedia", sumber: "Pembelian", tglAkuisisi: "14 Mar 2023", harga: 68000 },
  { inv: "INV00304", judul: "PKn Kelas 9", rak: "RAK-D1", kondisi: "Baik", status: "Dipinjam", sumber: "Pembelian", tglAkuisisi: "14 Mar 2023", harga: 70000 },
  { inv: "INV00303", judul: "Atlas Indonesia", rak: "RAK-E1", kondisi: "Baik", status: "Dipinjam", sumber: "Hibah", tglAkuisisi: "20 Apr 2021", harga: 0 },
];

// ── Pengadaan data ────────────────────────────────────────────────────────────
const pengadaanData = [
  { kode: "PRC-20260610-001", sumber: "Pembelian", supplier: "CV. Penerbit Nusantara", noInvoice: "INV/2026/0612", tgl: "10 Jun 2026", jumlahJudul: 5, jumlahEks: 20, total: 1850000, status: "Selesai" },
  { kode: "PRC-20260501-002", sumber: "Bantuan Pemerintah", supplier: "Dinas Pendidikan Kab. Bandung Barat", noInvoice: "-", tgl: "01 Mei 2026", jumlahJudul: 12, jumlahEks: 48, total: 0, status: "Selesai" },
  { kode: "PRC-20260315-003", sumber: "Hibah", supplier: "Yayasan Al-Ihsan", noInvoice: "-", tgl: "15 Mar 2026", jumlahJudul: 8, jumlahEks: 24, total: 0, status: "Selesai" },
  { kode: "PRC-20260110-004", sumber: "Pembelian", supplier: "Toko Buku Gramedia Bandung", noInvoice: "GRM/2026/0115", tgl: "10 Jan 2026", jumlahJudul: 10, jumlahEks: 35, total: 2740000, status: "Selesai" },
  { kode: "PRC-20260702-005", sumber: "Pembelian", supplier: "CV. Penerbit Nusantara", noInvoice: "-", tgl: "02 Jul 2026", jumlahJudul: 3, jumlahEks: 9, total: 720000, status: "Proses" },
];

const pengadaanItems = [
  { pengadaan: "PRC-20260610-001", judul: "Tafsir Al-Muyassar Jilid 2", jumlah: 4, harga: 125000, subtotal: 500000 },
  { pengadaan: "PRC-20260610-001", judul: "Fiqih Kontemporer", jumlah: 3, harga: 95000, subtotal: 285000 },
  { pengadaan: "PRC-20260610-001", judul: "Kimia SMP", jumlah: 5, harga: 88000, subtotal: 440000 },
  { pengadaan: "PRC-20260610-001", judul: "Sejarah Kebudayaan Islam Kelas 7", jumlah: 4, harga: 75000, subtotal: 300000 },
  { pengadaan: "PRC-20260610-001", judul: "Atlas Dunia Modern", jumlah: 4, harga: 81250, subtotal: 325000 },
];

// ── Insiden data ──────────────────────────────────────────────────────────────
const insidenData = [
  { kode: "INC-20260702-001", inv: "INV00245", judul: "Atlas Indonesia", jenis: "Rusak", tgl: "02 Jul 2026", sebab: "Terkena air saat dipinjam", peminjam: "Rizki Hidayat / VII-C", status: "Dilaporkan", resolusi: "-" },
  { kode: "INC-20260628-002", inv: "INV00288", judul: "Tafsir Al-Azhar Jilid 3", jenis: "Hilang", tgl: "28 Jun 2026", sebab: "Tidak dikembalikan setelah masa pinjam", peminjam: "Faisal Rahman / IX-B", status: "Dalam Proses", resolusi: "Menunggu konfirmasi" },
  { kode: "INC-20260615-003", inv: "INV00201", judul: "IPS Terpadu Kelas 7", jenis: "Rusak Berat", tgl: "15 Jun 2026", sebab: "Sampul robek dan halaman lepas", peminjam: "Stok Opname", status: "Selesai", resolusi: "Dimusnahkan — dicatat sebagai tidak aktif" },
  { kode: "INC-20260601-004", inv: "INV00190", judul: "Matematika Kelas 7", jenis: "Hilang", tgl: "01 Jun 2026", sebab: "Hilang saat dipinjam", peminjam: "Halimah Sadiyah / VIII-A", status: "Selesai", resolusi: "Denda penggantian Rp 85.000 sudah dibayar" },
  { kode: "INC-20260520-005", inv: "INV00155", judul: "Bahasa Indonesia Kelas 8", jenis: "Rusak", tgl: "20 Mei 2026", sebab: "Halaman sobek sebagian", peminjam: "Stok Opname", status: "Selesai", resolusi: "Diperbaiki oleh petugas" },
];

// ── Stok Opname data ──────────────────────────────────────────────────────────
const stokOpnameList = [
  { kode: "OPN-20260601-001", tgl: "01 Jun 2026", petugas: "Nining Rahayu, S.Pd", totalDiperiksa: 312, sesuai: 298, tidakSesuai: 8, tidakDitemukan: 6, status: "Selesai" },
  { kode: "OPN-20260101-002", tgl: "01 Jan 2026", petugas: "Admin Perpustakaan", totalDiperiksa: 289, sesuai: 276, tidakSesuai: 7, tidakDitemukan: 6, status: "Selesai" },
  { kode: "OPN-20250701-003", tgl: "01 Jul 2025", petugas: "Admin Perpustakaan", totalDiperiksa: 254, sesuai: 243, tidakSesuai: 5, tidakDitemukan: 6, status: "Selesai" },
];

const stokOpnameDetails = [
  { inv: "INV00245", judul: "Atlas Indonesia", statusSistem: "Tersedia", statusFisik: "Dipinjam", kondisi: "Baik", keterangan: "Ada di tangan Rizki Hidayat" },
  { inv: "INV00188", judul: "Tafsir Al-Azhar Jilid 1", statusSistem: "Tersedia", statusFisik: "Tidak Ditemukan", kondisi: "-", keterangan: "Tidak ada di rak maupun catatan" },
  { inv: "INV00201", judul: "IPS Terpadu Kelas 7", statusSistem: "Tersedia", statusFisik: "Rusak Berat", kondisi: "Rusak Berat", keterangan: "Butuh penyusutan" },
  { inv: "INV00155", judul: "Bahasa Indonesia Kelas 8", statusSistem: "Tersedia", statusFisik: "Rusak Ringan", kondisi: "Rusak Ringan", keterangan: "Halaman sobek, masih bisa dipakai" },
  { inv: "INV00099", judul: "Kimia SMP Kelas 8", statusSistem: "Tersedia", statusFisik: "Tidak Ditemukan", kondisi: "-", keterangan: "Tidak ditemukan di semua rak" },
  { inv: "INV00312", judul: "Tafsir Al-Muyassar Jilid 2", statusSistem: "Tersedia", statusFisik: "Tersedia", kondisi: "Baik", keterangan: "" },
  { inv: "INV00311", judul: "IPA Terpadu SMP Kelas 8", statusSistem: "Dipinjam", statusFisik: "Dipinjam", kondisi: "Baik", keterangan: "" },
  { inv: "INV00309", judul: "Matematika SMP Kelas 9", statusSistem: "Tersedia", statusFisik: "Tersedia", kondisi: "Rusak Ringan", keterangan: "Kondisi menurun sejak opname sebelumnya" },
];

// ── EksemplarPage ─────────────────────────────────────────────────────────────
function EksemplarPage() {
  const [search, setSearch] = useState("");
  const [filterStatus, setFilterStatus] = useState("Semua");
  const [filterKondisi, setFilterKondisi] = useState("Semua");
  const [selectedInv, setSelectedInv] = useState<string | null>(null);

  const statusOpts = ["Semua", "Tersedia", "Dipinjam", "Tidak Aktif"];
  const kondisiOpts = ["Semua", "Baik", "Rusak Ringan", "Rusak Berat"];

  const filtered = eksemplarData.filter((e) => {
    const matchSearch =
      e.inv.toLowerCase().includes(search.toLowerCase()) ||
      e.judul.toLowerCase().includes(search.toLowerCase()) ||
      e.rak.toLowerCase().includes(search.toLowerCase());
    const matchStatus = filterStatus === "Semua" || e.status === filterStatus;
    const matchKondisi = filterKondisi === "Semua" || e.kondisi === filterKondisi;
    return matchSearch && matchStatus && matchKondisi;
  });

  const detail = eksemplarData.find((e) => e.inv === selectedInv);

  const statCounts = {
    tersedia: eksemplarData.filter((e) => e.status === "Tersedia").length,
    dipinjam: eksemplarData.filter((e) => e.status === "Dipinjam").length,
    rusakRingan: eksemplarData.filter((e) => e.kondisi === "Rusak Ringan").length,
    tidakAktif: eksemplarData.filter((e) => e.status === "Tidak Aktif").length,
  };

  return (
    <div className="space-y-5">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
            Eksemplar Buku
          </h1>
          <p className="text-sm text-muted-foreground mt-0.5">
            {eksemplarData.length} eksemplar tercatat · {statCounts.tersedia} tersedia · {statCounts.dipinjam} dipinjam
          </p>
        </div>
        <div className="flex items-center gap-2">
          <button className="flex items-center gap-2 px-3 py-2 text-sm bg-card border border-border rounded-lg hover:bg-secondary transition-colors text-foreground">
            <Download size={14} />
            Ekspor
          </button>
          <button className="flex items-center gap-2 px-3 py-2 text-sm bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity">
            <Plus size={14} />
            Tambah Eksemplar
          </button>
        </div>
      </div>

      {/* Mini stat strip */}
      <div className="grid grid-cols-4 gap-3">
        {[
          { label: "Tersedia", val: statCounts.tersedia, color: "border-l-emerald-500 bg-emerald-50" },
          { label: "Dipinjam", val: statCounts.dipinjam, color: "border-l-blue-500 bg-blue-50" },
          { label: "Rusak Ringan", val: statCounts.rusakRingan, color: "border-l-amber-500 bg-amber-50" },
          { label: "Tidak Aktif", val: statCounts.tidakAktif, color: "border-l-red-500 bg-red-50" },
        ].map((s) => (
          <div key={s.label} className={`rounded-lg border border-border border-l-4 px-4 py-3 ${s.color}`}>
            <div className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>{s.val}</div>
            <div className="text-xs text-muted-foreground mt-0.5">{s.label}</div>
          </div>
        ))}
      </div>

      <div className="grid grid-cols-1 gap-4 lg:grid-cols-5">
        {/* Table */}
        <div className="lg:col-span-3 bg-card border border-border rounded-lg overflow-hidden">
          {/* Toolbar */}
          <div className="flex flex-wrap items-center gap-2 px-4 py-3 border-b border-border">
            <div className="flex items-center gap-2 flex-1 min-w-[180px] bg-input-background border border-border rounded-lg px-3 py-2">
              <Search size={14} className="text-muted-foreground shrink-0" />
              <input
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                placeholder="Cari kode inv., judul, rak..."
                className="bg-transparent text-xs outline-none flex-1 text-foreground placeholder:text-muted-foreground"
              />
            </div>
            <select
              value={filterStatus}
              onChange={(e) => setFilterStatus(e.target.value)}
              className="text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none"
            >
              {statusOpts.map((o) => <option key={o}>{o}</option>)}
            </select>
            <select
              value={filterKondisi}
              onChange={(e) => setFilterKondisi(e.target.value)}
              className="text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none"
            >
              {kondisiOpts.map((o) => <option key={o}>{o}</option>)}
            </select>
          </div>

          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead>
                <tr className="bg-muted/40">
                  {["Kode Inv.", "Judul", "Rak", "Kondisi", "Status", ""].map((h) => (
                    <th key={h} className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground whitespace-nowrap">{h}</th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {filtered.map((row) => (
                  <tr
                    key={row.inv}
                    onClick={() => setSelectedInv(row.inv === selectedInv ? null : row.inv)}
                    className={`border-t border-border cursor-pointer transition-colors ${selectedInv === row.inv ? "bg-secondary/60" : "hover:bg-muted/20"}`}
                  >
                    <td className="px-4 py-3 font-mono text-xs text-muted-foreground">{row.inv}</td>
                    <td className="px-4 py-3 text-xs font-medium text-foreground max-w-[160px] truncate">{row.judul}</td>
                    <td className="px-4 py-3 text-xs text-muted-foreground font-mono">{row.rak}</td>
                    <td className="px-4 py-3"><StatusBadge status={row.kondisi} /></td>
                    <td className="px-4 py-3"><StatusBadge status={row.status} /></td>
                    <td className="px-4 py-3">
                      <Eye size={13} className="text-muted-foreground" />
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
          <div className="px-4 py-3 border-t border-border text-xs text-muted-foreground">
            Menampilkan {filtered.length} dari {eksemplarData.length} eksemplar
          </div>
        </div>

        {/* Detail panel */}
        <div className="lg:col-span-2">
          {detail ? (
            <div className="bg-card border border-border rounded-lg overflow-hidden sticky top-0">
              <div className="flex items-center justify-between px-4 py-3 border-b border-border bg-secondary/20">
                <h3 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
                  Detail Eksemplar
                </h3>
                <button onClick={() => setSelectedInv(null)} className="text-muted-foreground hover:text-foreground">
                  <X size={14} />
                </button>
              </div>
              <div className="p-4 space-y-3">
                <div className="flex gap-2">
                  <StatusBadge status={detail.status} />
                  <StatusBadge status={detail.kondisi} />
                </div>
                <div className="space-y-2">
                  {[
                    { label: "Kode Inventaris", val: detail.inv },
                    { label: "Judul Buku", val: detail.judul },
                    { label: "Lokasi Rak", val: detail.rak },
                    { label: "Sumber Perolehan", val: detail.sumber },
                    { label: "Tanggal Akuisisi", val: detail.tglAkuisisi },
                    { label: "Harga Perolehan", val: detail.harga > 0 ? `Rp ${detail.harga.toLocaleString("id-ID")}` : "Hibah / Bantuan" },
                  ].map((r) => (
                    <div key={r.label} className="flex justify-between gap-2 text-xs py-1.5 border-b border-border last:border-0">
                      <span className="text-muted-foreground">{r.label}</span>
                      <span className={`font-medium text-right ${r.label === "Kode Inventaris" ? "font-mono" : ""}`}>{r.val}</span>
                    </div>
                  ))}
                </div>
                <div className="pt-1 flex gap-2">
                  <button className="flex-1 py-2 text-xs font-medium bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity">
                    Edit Eksemplar
                  </button>
                  <button className="flex-1 py-2 text-xs font-medium bg-card border border-border rounded-lg hover:bg-secondary transition-colors text-foreground">
                    Cetak Label
                  </button>
                </div>
              </div>
            </div>
          ) : (
            <div className="bg-card border border-border rounded-lg p-8 flex flex-col items-center justify-center text-center min-h-[280px]">
              <BookCopy size={32} className="text-muted-foreground/30 mb-3" />
              <p className="text-sm text-muted-foreground">Klik baris di tabel untuk melihat detail eksemplar</p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}

// ── PengadaanPage ─────────────────────────────────────────────────────────────
function PengadaanPage() {
  const [tab, setTab] = useState<"daftar" | "detail">("daftar");
  const [selectedKode, setSelectedKode] = useState<string | null>(null);
  const [showForm, setShowForm] = useState(false);

  const selected = pengadaanData.find((p) => p.kode === selectedKode);

  const totalAnggaran = pengadaanData.filter((p) => p.sumber === "Pembelian").reduce((s, p) => s + p.total, 0);
  const totalEksemplar = pengadaanData.reduce((s, p) => s + p.jumlahEks, 0);

  return (
    <div className="space-y-5">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
            Pengadaan Buku
          </h1>
          <p className="text-sm text-muted-foreground mt-0.5">
            {pengadaanData.length} pengadaan · {totalEksemplar} eksemplar diterima
          </p>
        </div>
        <button
          onClick={() => setShowForm(true)}
          className="flex items-center gap-2 px-3 py-2 text-sm bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity"
        >
          <Plus size={14} />
          Catat Pengadaan
        </button>
      </div>

      {/* Summary */}
      <div className="grid grid-cols-3 gap-3">
        {[
          { label: "Total Anggaran Pembelian", val: `Rp ${totalAnggaran.toLocaleString("id-ID")}`, color: "bg-[#145048]" },
          { label: "Total Eksemplar Masuk", val: totalEksemplar.toString(), color: "bg-[#2a8a76]" },
          { label: "Dari Hibah / Bantuan", val: pengadaanData.filter((p) => p.sumber !== "Pembelian").reduce((s, p) => s + p.jumlahEks, 0).toString() + " eks", color: "bg-[#bf7c33]" },
        ].map((c) => (
          <div key={c.label} className="bg-card border border-border rounded-lg p-4 flex items-center gap-3">
            <div className={`w-9 h-9 rounded-lg flex items-center justify-center text-white shrink-0 ${c.color}`}>
              <ShoppingCart size={16} />
            </div>
            <div>
              <div className="text-base font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>{c.val}</div>
              <div className="text-xs text-muted-foreground">{c.label}</div>
            </div>
          </div>
        ))}
      </div>

      {/* Tabs */}
      <div className="flex gap-1 bg-muted rounded-lg p-1 w-fit">
        {(["daftar", "detail"] as const).map((t) => (
          <button
            key={t}
            onClick={() => setTab(t)}
            className={`px-4 py-1.5 rounded-md text-xs font-medium transition-colors ${
              tab === t ? "bg-card text-foreground shadow-sm" : "text-muted-foreground hover:text-foreground"
            }`}
          >
            {t === "daftar" ? "Daftar Pengadaan" : "Rincian Item"}
          </button>
        ))}
      </div>

      {tab === "daftar" && (
        <div className="bg-card border border-border rounded-lg overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead>
                <tr className="bg-muted/40">
                  {["Kode Pengadaan", "Sumber", "Supplier/Donatur", "No. Invoice", "Tanggal", "Judul", "Eksemplar", "Total", "Status", ""].map((h) => (
                    <th key={h} className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground whitespace-nowrap">{h}</th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {pengadaanData.map((row, i) => (
                  <tr
                    key={i}
                    className={`border-t border-border hover:bg-muted/20 transition-colors cursor-pointer ${selectedKode === row.kode ? "bg-secondary/40" : ""}`}
                    onClick={() => { setSelectedKode(row.kode); setTab("detail"); }}
                  >
                    <td className="px-4 py-3 font-mono text-xs text-muted-foreground whitespace-nowrap">{row.kode}</td>
                    <td className="px-4 py-3">
                      <span className={`inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${
                        row.sumber === "Pembelian" ? "bg-blue-50 text-blue-700 border border-blue-200"
                          : row.sumber === "Hibah" ? "bg-purple-50 text-purple-700 border border-purple-200"
                          : "bg-emerald-50 text-emerald-700 border border-emerald-200"
                      }`}>{row.sumber}</span>
                    </td>
                    <td className="px-4 py-3 text-xs text-foreground max-w-[160px] truncate">{row.supplier}</td>
                    <td className="px-4 py-3 font-mono text-xs text-muted-foreground">{row.noInvoice}</td>
                    <td className="px-4 py-3 text-xs text-muted-foreground whitespace-nowrap">{row.tgl}</td>
                    <td className="px-4 py-3 text-xs font-mono text-foreground text-center">{row.jumlahJudul}</td>
                    <td className="px-4 py-3 text-xs font-mono text-foreground text-center">{row.jumlahEks}</td>
                    <td className="px-4 py-3 text-xs font-mono text-foreground whitespace-nowrap">
                      {row.total > 0 ? `Rp ${row.total.toLocaleString("id-ID")}` : "—"}
                    </td>
                    <td className="px-4 py-3">
                      <StatusBadge status={row.status} />
                    </td>
                    <td className="px-4 py-3">
                      <Eye size={13} className="text-muted-foreground" />
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {tab === "detail" && (
        <div className="space-y-4">
          {selected ? (
            <>
              {/* Header card */}
              <div className="bg-card border border-border rounded-lg p-4">
                <div className="flex items-start justify-between gap-4">
                  <div>
                    <div className="font-mono text-xs text-muted-foreground mb-1">{selected.kode}</div>
                    <h2 className="text-base font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
                      {selected.supplier}
                    </h2>
                    <div className="flex items-center gap-3 mt-2 text-xs text-muted-foreground flex-wrap">
                      <span>Tanggal: <strong className="text-foreground">{selected.tgl}</strong></span>
                      <span>·</span>
                      <span>Sumber: <strong className="text-foreground">{selected.sumber}</strong></span>
                      {selected.noInvoice !== "-" && (
                        <>
                          <span>·</span>
                          <span>Invoice: <strong className="font-mono text-foreground">{selected.noInvoice}</strong></span>
                        </>
                      )}
                    </div>
                  </div>
                  <div className="text-right shrink-0">
                    <div className="text-lg font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
                      {selected.total > 0 ? `Rp ${selected.total.toLocaleString("id-ID")}` : "Gratis"}
                    </div>
                    <StatusBadge status={selected.status} />
                  </div>
                </div>
              </div>

              {/* Items table */}
              <div className="bg-card border border-border rounded-lg overflow-hidden">
                <div className="flex items-center justify-between px-4 py-3 border-b border-border">
                  <h3 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
                    Item Pengadaan
                  </h3>
                  <button className="flex items-center gap-1.5 text-xs text-muted-foreground hover:text-foreground border border-border rounded-lg px-3 py-1.5 bg-card hover:bg-secondary transition-colors">
                    <Download size={12} />
                    Cetak BA Penerimaan
                  </button>
                </div>
                <div className="overflow-x-auto">
                  <table className="w-full text-sm">
                    <thead>
                      <tr className="bg-muted/40">
                        {["#", "Judul Buku", "Jumlah", "Harga Satuan", "Subtotal"].map((h) => (
                          <th key={h} className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground">{h}</th>
                        ))}
                      </tr>
                    </thead>
                    <tbody>
                      {pengadaanItems.map((item, i) => (
                        <tr key={i} className="border-t border-border hover:bg-muted/20 transition-colors">
                          <td className="px-4 py-3 text-xs text-muted-foreground">{i + 1}</td>
                          <td className="px-4 py-3 text-xs font-medium text-foreground">{item.judul}</td>
                          <td className="px-4 py-3 text-xs font-mono text-center text-foreground">{item.jumlah}</td>
                          <td className="px-4 py-3 text-xs font-mono text-foreground">Rp {item.harga.toLocaleString("id-ID")}</td>
                          <td className="px-4 py-3 text-xs font-mono font-medium text-foreground">Rp {item.subtotal.toLocaleString("id-ID")}</td>
                        </tr>
                      ))}
                      <tr className="border-t-2 border-border bg-secondary/30">
                        <td colSpan={4} className="px-4 py-3 text-xs font-bold text-right text-foreground">TOTAL</td>
                        <td className="px-4 py-3 text-xs font-bold font-mono text-foreground">
                          Rp {pengadaanItems.reduce((s, i) => s + i.subtotal, 0).toLocaleString("id-ID")}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </>
          ) : (
            <div className="bg-card border border-border rounded-lg p-12 flex flex-col items-center justify-center text-center">
              <ShoppingCart size={32} className="text-muted-foreground/30 mb-3" />
              <p className="text-sm text-muted-foreground">Pilih pengadaan dari tab Daftar untuk melihat rincian item</p>
            </div>
          )}
        </div>
      )}

      {/* New procurement modal-like overlay */}
      {showForm && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
          <div className="bg-card rounded-xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
            <div className="flex items-center justify-between px-5 py-4 border-b border-border">
              <h2 className="text-sm font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
                Catat Pengadaan Baru
              </h2>
              <button onClick={() => setShowForm(false)} className="text-muted-foreground hover:text-foreground">
                <X size={16} />
              </button>
            </div>
            <div className="p-5 space-y-3">
              {[
                { label: "Sumber Perolehan", type: "select", opts: ["Pembelian", "Hibah", "Bantuan Pemerintah"] },
                { label: "Supplier / Donatur", type: "text", placeholder: "Nama toko / lembaga" },
                { label: "Nomor Invoice", type: "text", placeholder: "Opsional" },
                { label: "Tanggal Pengadaan", type: "date", placeholder: "" },
                { label: "Catatan", type: "textarea", placeholder: "Keterangan tambahan..." },
              ].map((f) => (
                <div key={f.label} className="space-y-1">
                  <label className="text-xs font-medium text-foreground">{f.label}</label>
                  {f.type === "select" ? (
                    <select className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring">
                      {f.opts!.map((o) => <option key={o}>{o}</option>)}
                    </select>
                  ) : f.type === "textarea" ? (
                    <textarea rows={2} placeholder={f.placeholder}
                      className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring resize-none placeholder:text-muted-foreground" />
                  ) : (
                    <input type={f.type} placeholder={f.placeholder}
                      className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring placeholder:text-muted-foreground" />
                  )}
                </div>
              ))}
            </div>
            <div className="flex gap-2 px-5 pb-5">
              <button onClick={() => setShowForm(false)} className="flex-1 py-2 text-xs font-medium bg-card border border-border rounded-lg hover:bg-secondary transition-colors text-foreground">
                Batal
              </button>
              <button onClick={() => setShowForm(false)} className="flex-1 py-2 text-xs font-medium bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity">
                Simpan & Tambah Item
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

// ── InsidenPage ───────────────────────────────────────────────────────────────
function InsidenPage() {
  const [filterJenis, setFilterJenis] = useState("Semua");
  const [filterStatus, setFilterStatus] = useState("Semua");
  const [search, setSearch] = useState("");
  const [showForm, setShowForm] = useState(false);
  const [selectedKode, setSelectedKode] = useState<string | null>(null);

  const jenisOpts = ["Semua", "Rusak", "Rusak Berat", "Hilang"];
  const statusOpts = ["Semua", "Dilaporkan", "Dalam Proses", "Selesai"];

  const filtered = insidenData.filter((d) => {
    const matchSearch = d.judul.toLowerCase().includes(search.toLowerCase()) || d.inv.toLowerCase().includes(search.toLowerCase()) || d.peminjam.toLowerCase().includes(search.toLowerCase());
    const matchJenis = filterJenis === "Semua" || d.jenis === filterJenis;
    const matchStatus = filterStatus === "Semua" || d.status === filterStatus;
    return matchSearch && matchJenis && matchStatus;
  });

  const selectedIncident = insidenData.find((d) => d.kode === selectedKode);

  return (
    <div className="space-y-5">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
            Insiden Buku
          </h1>
          <p className="text-sm text-muted-foreground mt-0.5">
            Laporan kerusakan dan kehilangan eksemplar
          </p>
        </div>
        <button
          onClick={() => setShowForm(true)}
          className="flex items-center gap-2 px-3 py-2 text-sm bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity"
        >
          <Plus size={14} />
          Laporkan Insiden
        </button>
      </div>

      {/* Summary */}
      <div className="grid grid-cols-4 gap-3">
        {[
          { label: "Total Insiden", val: insidenData.length, color: "bg-gray-100 border-gray-300 text-gray-800" },
          { label: "Dilaporkan", val: insidenData.filter((d) => d.status === "Dilaporkan").length, color: "bg-blue-50 border-blue-200 text-blue-800" },
          { label: "Dalam Proses", val: insidenData.filter((d) => d.status === "Dalam Proses").length, color: "bg-amber-50 border-amber-200 text-amber-800" },
          { label: "Selesai", val: insidenData.filter((d) => d.status === "Selesai").length, color: "bg-emerald-50 border-emerald-200 text-emerald-800" },
        ].map((s) => (
          <div key={s.label} className={`rounded-lg border px-4 py-3 ${s.color}`}>
            <div className="text-xl font-bold" style={{ fontFamily: "'Roboto Slab', serif" }}>{s.val}</div>
            <div className="text-xs font-medium mt-0.5">{s.label}</div>
          </div>
        ))}
      </div>

      <div className="grid grid-cols-1 gap-4 lg:grid-cols-5">
        {/* List */}
        <div className="lg:col-span-3 bg-card border border-border rounded-lg overflow-hidden">
          <div className="flex flex-wrap items-center gap-2 px-4 py-3 border-b border-border">
            <div className="flex items-center gap-2 flex-1 min-w-[160px] bg-input-background border border-border rounded-lg px-3 py-2">
              <Search size={14} className="text-muted-foreground shrink-0" />
              <input
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                placeholder="Cari kode, judul, anggota..."
                className="bg-transparent text-xs outline-none flex-1 text-foreground placeholder:text-muted-foreground"
              />
            </div>
            <select value={filterJenis} onChange={(e) => setFilterJenis(e.target.value)}
              className="text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none">
              {jenisOpts.map((o) => <option key={o}>{o}</option>)}
            </select>
            <select value={filterStatus} onChange={(e) => setFilterStatus(e.target.value)}
              className="text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none">
              {statusOpts.map((o) => <option key={o}>{o}</option>)}
            </select>
          </div>

          <div className="divide-y divide-border">
            {filtered.map((inc) => (
              <button
                key={inc.kode}
                onClick={() => setSelectedKode(inc.kode === selectedKode ? null : inc.kode)}
                className={`w-full text-left px-4 py-3 flex items-start gap-3 transition-colors ${
                  selectedKode === inc.kode ? "bg-secondary/60" : "hover:bg-muted/20"
                }`}
              >
                <div className={`mt-1 w-8 h-8 rounded-lg flex items-center justify-center shrink-0 text-white text-[10px] font-bold ${
                  inc.jenis === "Hilang" ? "bg-red-500" : inc.jenis === "Rusak Berat" ? "bg-orange-500" : "bg-amber-500"
                }`}>
                  {inc.jenis === "Hilang" ? "HL" : inc.jenis === "Rusak Berat" ? "RB" : "RK"}
                </div>
                <div className="flex-1 min-w-0">
                  <div className="flex items-start justify-between gap-2">
                    <span className="font-medium text-xs text-foreground truncate">{inc.judul}</span>
                    <StatusBadge status={inc.status} />
                  </div>
                  <div className="text-[10px] text-muted-foreground font-mono mt-0.5">{inc.kode} · {inc.inv}</div>
                  <div className="text-xs text-muted-foreground mt-0.5 truncate">{inc.peminjam} · {inc.tgl}</div>
                </div>
              </button>
            ))}
          </div>
        </div>

        {/* Detail */}
        <div className="lg:col-span-2">
          {selectedIncident ? (
            <div className="bg-card border border-border rounded-lg overflow-hidden sticky top-0">
              <div className="flex items-center justify-between px-4 py-3 border-b border-border bg-secondary/20">
                <h3 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>Detail Insiden</h3>
                <button onClick={() => setSelectedKode(null)} className="text-muted-foreground hover:text-foreground"><X size={14} /></button>
              </div>
              <div className="p-4 space-y-3">
                <div className={`rounded-lg px-3 py-2 text-xs font-medium flex items-center gap-2 ${
                  selectedIncident.jenis === "Hilang" ? "bg-red-50 text-red-700 border border-red-200"
                    : "bg-amber-50 text-amber-700 border border-amber-200"
                }`}>
                  <AlertTriangle size={13} />
                  {selectedIncident.jenis}
                </div>
                <div className="space-y-2">
                  {[
                    { label: "Kode Insiden", val: selectedIncident.kode },
                    { label: "Kode Inventaris", val: selectedIncident.inv },
                    { label: "Judul Buku", val: selectedIncident.judul },
                    { label: "Tanggal", val: selectedIncident.tgl },
                    { label: "Pihak Terkait", val: selectedIncident.peminjam },
                    { label: "Deskripsi", val: selectedIncident.sebab },
                  ].map((r) => (
                    <div key={r.label} className="flex justify-between gap-2 text-xs py-1.5 border-b border-border last:border-0">
                      <span className="text-muted-foreground shrink-0">{r.label}</span>
                      <span className="font-medium text-right text-foreground">{r.val}</span>
                    </div>
                  ))}
                </div>

                {selectedIncident.resolusi !== "-" && selectedIncident.resolusi !== "" && (
                  <div className="bg-emerald-50 border border-emerald-200 rounded-lg p-3 text-xs text-emerald-800">
                    <div className="font-semibold mb-1 flex items-center gap-1.5">
                      <CheckCircle2 size={12} />
                      Resolusi
                    </div>
                    {selectedIncident.resolusi}
                  </div>
                )}

                <div className="flex gap-2 pt-1">
                  {selectedIncident.status !== "Selesai" && (
                    <button className="flex-1 py-2 text-xs font-medium bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity">
                      Tandai Selesai
                    </button>
                  )}
                  <button className="flex-1 py-2 text-xs font-medium bg-card border border-border rounded-lg hover:bg-secondary transition-colors text-foreground">
                    Edit
                  </button>
                </div>
              </div>
            </div>
          ) : (
            <div className="bg-card border border-border rounded-lg p-8 flex flex-col items-center justify-center text-center min-h-[280px]">
              <AlertTriangle size={32} className="text-muted-foreground/30 mb-3" />
              <p className="text-sm text-muted-foreground">Pilih insiden dari daftar untuk melihat detail</p>
            </div>
          )}
        </div>
      </div>

      {/* New incident form modal */}
      {showForm && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
          <div className="bg-card rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
            <div className="flex items-center justify-between px-5 py-4 border-b border-border">
              <h2 className="text-sm font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>Laporkan Insiden Buku</h2>
              <button onClick={() => setShowForm(false)} className="text-muted-foreground hover:text-foreground"><X size={16} /></button>
            </div>
            <div className="p-5 space-y-3">
              {[
                { label: "Kode Inventaris", type: "text", placeholder: "Contoh: INV00245" },
                { label: "Jenis Insiden", type: "select", opts: ["Rusak", "Rusak Berat", "Hilang"] },
                { label: "Tanggal Insiden", type: "date", placeholder: "" },
                { label: "Pihak Terkait (opsional)", type: "text", placeholder: "Nama anggota / Stok Opname" },
                { label: "Deskripsi Kejadian", type: "textarea", placeholder: "Jelaskan kondisi atau kejadian..." },
              ].map((f) => (
                <div key={f.label} className="space-y-1">
                  <label className="text-xs font-medium text-foreground">{f.label}</label>
                  {f.type === "select" ? (
                    <select className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring">
                      {f.opts!.map((o) => <option key={o}>{o}</option>)}
                    </select>
                  ) : f.type === "textarea" ? (
                    <textarea rows={3} placeholder={f.placeholder}
                      className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring resize-none placeholder:text-muted-foreground" />
                  ) : (
                    <input type={f.type} placeholder={f.placeholder}
                      className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring placeholder:text-muted-foreground" />
                  )}
                </div>
              ))}
            </div>
            <div className="flex gap-2 px-5 pb-5">
              <button onClick={() => setShowForm(false)} className="flex-1 py-2 text-xs font-medium bg-card border border-border rounded-lg hover:bg-secondary transition-colors text-foreground">Batal</button>
              <button onClick={() => setShowForm(false)} className="flex-1 py-2 text-xs font-medium bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity">Simpan Laporan</button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

// ── StokOpnamePage ────────────────────────────────────────────────────────────
function StokOpnamePage() {
  const [tab, setTab] = useState<"daftar" | "detail" | "baru">("daftar");
  const [selectedKode, setSelectedKode] = useState<string | null>(null);
  const [filterDetail, setFilterDetail] = useState("Semua");
  const [searchDetail, setSearchDetail] = useState("");

  const filterOpts = ["Semua", "Sesuai", "Tidak Sesuai", "Tidak Ditemukan"];

  const filteredDetails = stokOpnameDetails.filter((d) => {
    const matchSearch =
      d.inv.toLowerCase().includes(searchDetail.toLowerCase()) ||
      d.judul.toLowerCase().includes(searchDetail.toLowerCase());
    const matchFilter =
      filterDetail === "Semua" ||
      (filterDetail === "Sesuai" && d.statusSistem === d.statusFisik) ||
      (filterDetail === "Tidak Sesuai" && d.statusFisik !== d.statusSistem && d.statusFisik !== "Tidak Ditemukan") ||
      (filterDetail === "Tidak Ditemukan" && d.statusFisik === "Tidak Ditemukan");
    return matchSearch && matchFilter;
  });

  const sesuai = stokOpnameDetails.filter((d) => d.statusSistem === d.statusFisik).length;
  const tidakSesuai = stokOpnameDetails.filter((d) => d.statusFisik !== d.statusSistem && d.statusFisik !== "Tidak Ditemukan").length;
  const tidakDitemukan = stokOpnameDetails.filter((d) => d.statusFisik === "Tidak Ditemukan").length;

  return (
    <div className="space-y-5">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
            Stok Opname
          </h1>
          <p className="text-sm text-muted-foreground mt-0.5">Audit fisik koleksi perpustakaan</p>
        </div>
        <button
          onClick={() => setTab("baru")}
          className="flex items-center gap-2 px-3 py-2 text-sm bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity"
        >
          <Plus size={14} />
          Mulai Stok Opname
        </button>
      </div>

      {/* Tabs */}
      <div className="flex gap-1 bg-muted rounded-lg p-1 w-fit">
        {(["daftar", "detail", "baru"] as const).map((t) => (
          <button
            key={t}
            onClick={() => setTab(t)}
            className={`px-4 py-1.5 rounded-md text-xs font-medium transition-colors ${
              tab === t ? "bg-card text-foreground shadow-sm" : "text-muted-foreground hover:text-foreground"
            }`}
          >
            {t === "daftar" ? "Riwayat Opname" : t === "detail" ? "Detail Terakhir" : "Opname Baru"}
          </button>
        ))}
      </div>

      {tab === "daftar" && (
        <div className="space-y-4">
          <div className="grid grid-cols-3 gap-3">
            <div className="bg-card border border-border rounded-lg p-4">
              <div className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
                {stokOpnameList.length}
              </div>
              <div className="text-xs text-muted-foreground mt-0.5">Opname Dilakukan</div>
            </div>
            <div className="bg-card border border-border rounded-lg p-4">
              <div className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
                {stokOpnameList[0].tgl}
              </div>
              <div className="text-xs text-muted-foreground mt-0.5">Opname Terakhir</div>
            </div>
            <div className="bg-card border border-border rounded-lg p-4">
              <div className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
                {((stokOpnameList[0].sesuai / stokOpnameList[0].totalDiperiksa) * 100).toFixed(1)}%
              </div>
              <div className="text-xs text-muted-foreground mt-0.5">Akurasi Terakhir</div>
            </div>
          </div>

          <div className="bg-card border border-border rounded-lg overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="bg-muted/40">
                    {["Kode Opname", "Tanggal", "Petugas", "Diperiksa", "Sesuai", "Tidak Sesuai", "Tidak Ditemukan", "Status", ""].map((h) => (
                      <th key={h} className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground whitespace-nowrap">{h}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {stokOpnameList.map((row, i) => (
                    <tr
                      key={i}
                      onClick={() => { setSelectedKode(row.kode); setTab("detail"); }}
                      className="border-t border-border hover:bg-muted/20 transition-colors cursor-pointer"
                    >
                      <td className="px-4 py-3 font-mono text-xs text-muted-foreground">{row.kode}</td>
                      <td className="px-4 py-3 text-xs text-foreground">{row.tgl}</td>
                      <td className="px-4 py-3 text-xs text-foreground">{row.petugas}</td>
                      <td className="px-4 py-3 text-xs font-mono text-center text-foreground">{row.totalDiperiksa}</td>
                      <td className="px-4 py-3 text-xs font-mono text-center text-emerald-600 font-medium">{row.sesuai}</td>
                      <td className="px-4 py-3 text-xs font-mono text-center text-amber-600 font-medium">{row.tidakSesuai}</td>
                      <td className="px-4 py-3 text-xs font-mono text-center text-red-600 font-medium">{row.tidakDitemukan}</td>
                      <td className="px-4 py-3">
                        <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                          {row.status}
                        </span>
                      </td>
                      <td className="px-4 py-3">
                        <Eye size={13} className="text-muted-foreground" />
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}

      {tab === "detail" && (
        <div className="space-y-4">
          {/* Result summary */}
          <div className="grid grid-cols-4 gap-3">
            {[
              { label: "Total Diperiksa", val: stokOpnameDetails.length, color: "border-l-[#145048]" },
              { label: "Sesuai", val: sesuai, color: "border-l-emerald-500" },
              { label: "Tidak Sesuai", val: tidakSesuai, color: "border-l-amber-500" },
              { label: "Tidak Ditemukan", val: tidakDitemukan, color: "border-l-red-500" },
            ].map((s) => (
              <div key={s.label} className={`bg-card border border-border border-l-4 ${s.color} rounded-lg px-4 py-3`}>
                <div className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>{s.val}</div>
                <div className="text-xs text-muted-foreground mt-0.5">{s.label}</div>
              </div>
            ))}
          </div>

          {/* Accuracy bar */}
          <div className="bg-card border border-border rounded-lg p-4">
            <div className="flex items-center justify-between mb-2 text-xs">
              <span className="font-medium text-foreground">Tingkat Kesesuaian</span>
              <span className="font-bold text-foreground">{((sesuai / stokOpnameDetails.length) * 100).toFixed(1)}%</span>
            </div>
            <div className="h-3 bg-muted rounded-full overflow-hidden flex gap-0.5">
              <div className="bg-emerald-500 h-full rounded-l-full transition-all" style={{ width: `${(sesuai / stokOpnameDetails.length) * 100}%` }} />
              <div className="bg-amber-400 h-full transition-all" style={{ width: `${(tidakSesuai / stokOpnameDetails.length) * 100}%` }} />
              <div className="bg-red-400 h-full rounded-r-full transition-all" style={{ width: `${(tidakDitemukan / stokOpnameDetails.length) * 100}%` }} />
            </div>
            <div className="flex items-center gap-4 mt-2 text-[10px] text-muted-foreground">
              <span className="flex items-center gap-1"><span className="w-2 h-2 rounded-sm bg-emerald-500 inline-block" /> Sesuai</span>
              <span className="flex items-center gap-1"><span className="w-2 h-2 rounded-sm bg-amber-400 inline-block" /> Tidak Sesuai</span>
              <span className="flex items-center gap-1"><span className="w-2 h-2 rounded-sm bg-red-400 inline-block" /> Tidak Ditemukan</span>
            </div>
          </div>

          {/* Detail table */}
          <div className="bg-card border border-border rounded-lg overflow-hidden">
            <div className="flex flex-wrap items-center gap-2 px-4 py-3 border-b border-border">
              <div className="flex items-center gap-2 flex-1 min-w-[160px] bg-input-background border border-border rounded-lg px-3 py-2">
                <Search size={14} className="text-muted-foreground shrink-0" />
                <input
                  value={searchDetail}
                  onChange={(e) => setSearchDetail(e.target.value)}
                  placeholder="Cari kode inventaris, judul..."
                  className="bg-transparent text-xs outline-none flex-1 text-foreground placeholder:text-muted-foreground"
                />
              </div>
              <div className="flex items-center gap-1">
                {filterOpts.map((f) => (
                  <button
                    key={f}
                    onClick={() => setFilterDetail(f)}
                    className={`px-3 py-1.5 text-xs rounded-lg font-medium transition-colors ${
                      filterDetail === f ? "bg-primary text-primary-foreground" : "bg-card border border-border text-muted-foreground hover:bg-secondary"
                    }`}
                  >
                    {f}
                  </button>
                ))}
              </div>
              <button className="flex items-center gap-1.5 text-xs text-muted-foreground hover:text-foreground border border-border rounded-lg px-3 py-1.5 bg-card hover:bg-secondary transition-colors ml-auto">
                <Download size={12} />
                Ekspor Berita Acara
              </button>
            </div>
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="bg-muted/40">
                    {["Kode Inv.", "Judul Buku", "Status Sistem", "Status Fisik", "Kondisi", "Keterangan"].map((h) => (
                      <th key={h} className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground whitespace-nowrap">{h}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {filteredDetails.map((row, i) => {
                    const isMatch = row.statusSistem === row.statusFisik;
                    const isNotFound = row.statusFisik === "Tidak Ditemukan";
                    return (
                      <tr key={i} className={`border-t border-border transition-colors ${
                        isNotFound ? "bg-red-50/40 hover:bg-red-50/60"
                          : !isMatch ? "bg-amber-50/40 hover:bg-amber-50/60"
                          : "hover:bg-muted/20"
                      }`}>
                        <td className="px-4 py-3 font-mono text-xs text-muted-foreground">{row.inv}</td>
                        <td className="px-4 py-3 text-xs font-medium text-foreground max-w-[180px] truncate">{row.judul}</td>
                        <td className="px-4 py-3"><StatusBadge status={row.statusSistem} /></td>
                        <td className="px-4 py-3">
                          {isNotFound ? (
                            <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                              Tidak Ditemukan
                            </span>
                          ) : (
                            <StatusBadge status={row.statusFisik} />
                          )}
                        </td>
                        <td className="px-4 py-3 text-xs text-muted-foreground">{row.kondisi || "—"}</td>
                        <td className="px-4 py-3 text-xs text-muted-foreground max-w-[200px] truncate">{row.keterangan || "—"}</td>
                      </tr>
                    );
                  })}
                </tbody>
              </table>
            </div>
            <div className="px-4 py-3 border-t border-border text-xs text-muted-foreground">
              Menampilkan {filteredDetails.length} dari {stokOpnameDetails.length} data
            </div>
          </div>
        </div>
      )}

      {tab === "baru" && (
        <div className="max-w-2xl space-y-4">
          <div className="bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 flex items-start gap-3 text-sm text-amber-800">
            <AlertCircle size={15} className="shrink-0 mt-0.5 text-amber-600" />
            <span>
              Stok Opname akan mengunci status koleksi selama proses berlangsung. Pastikan tidak ada transaksi peminjaman aktif yang belum diproses sebelum memulai.
            </span>
          </div>

          <div className="bg-card border border-border rounded-lg overflow-hidden">
            <div className="px-4 py-3 border-b border-border bg-secondary/20">
              <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
                Pengaturan Stok Opname Baru
              </h2>
            </div>
            <div className="p-4 space-y-4">
              <div className="grid grid-cols-2 gap-3">
                <div className="space-y-1">
                  <label className="text-xs font-medium text-foreground">Kode Opname</label>
                  <input
                    readOnly
                    defaultValue="OPN-20260702-004"
                    className="w-full text-xs px-3 py-2 bg-muted border border-border rounded-lg text-muted-foreground font-mono outline-none"
                  />
                </div>
                <div className="space-y-1">
                  <label className="text-xs font-medium text-foreground">Tanggal Pelaksanaan</label>
                  <input
                    type="date"
                    defaultValue="2026-07-02"
                    className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring"
                  />
                </div>
              </div>

              <div className="space-y-1">
                <label className="text-xs font-medium text-foreground">Petugas Pelaksana</label>
                <select className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring">
                  <option>Admin Perpustakaan</option>
                  <option>Nining Rahayu, S.Pd</option>
                </select>
              </div>

              <div className="space-y-1">
                <label className="text-xs font-medium text-foreground">Cakupan Opname</label>
                <div className="grid grid-cols-3 gap-2">
                  {["Semua Koleksi", "Pilih Rak Tertentu", "Pilih Kategori"].map((opt) => (
                    <label key={opt} className="flex items-center gap-2 text-xs cursor-pointer">
                      <input
                        type="radio"
                        name="cakupan"
                        defaultChecked={opt === "Semua Koleksi"}
                        className="accent-primary"
                      />
                      <span className="text-foreground">{opt}</span>
                    </label>
                  ))}
                </div>
              </div>

              <div className="space-y-1">
                <label className="text-xs font-medium text-foreground">Catatan Awal</label>
                <textarea
                  rows={2}
                  placeholder="Keterangan atau instruksi untuk petugas..."
                  className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring resize-none placeholder:text-muted-foreground"
                />
              </div>

              {/* Preview stats */}
              <div className="bg-secondary/30 rounded-lg p-3 grid grid-cols-3 gap-3 text-center">
                {[
                  { label: "Eksemplar akan Diperiksa", val: "1.065" },
                  { label: "Judul Unik", val: "312" },
                  { label: "Estimasi Waktu", val: "± 3 jam" },
                ].map((s) => (
                  <div key={s.label}>
                    <div className="text-base font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>{s.val}</div>
                    <div className="text-[10px] text-muted-foreground mt-0.5">{s.label}</div>
                  </div>
                ))}
              </div>

              <div className="flex gap-2">
                <button onClick={() => setTab("daftar")} className="flex-1 py-2.5 text-xs font-medium bg-card border border-border rounded-lg hover:bg-secondary transition-colors text-foreground">
                  Batal
                </button>
                <button onClick={() => setTab("detail")} className="flex-1 flex items-center justify-center gap-2 py-2.5 text-xs font-medium bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity">
                  <ClipboardList size={14} />
                  Mulai Stok Opname
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

// ── Pengunjung data ───────────────────────────────────────────────────────────
const pengunjungHariIni = [
  { id: 1, nama: "Ahmad Fauzi Ramadhan", kodeAnggota: "MBR-2026-0148", jenis: "Siswa", kelas: "VIII-B", tujuan: "Meminjam Buku", masuk: "07:45", keluar: "08:10", tgl: "02 Jul 2026" },
  { id: 2, nama: "Siti Nurhaliza Putri", kodeAnggota: "MBR-2026-0147", jenis: "Siswa", kelas: "VII-A", tujuan: "Membaca", masuk: "08:02", keluar: "09:15", tgl: "02 Jul 2026" },
  { id: 3, nama: "Nining Rahayu, S.Pd", kodeAnggota: "MBR-GTK-009", jenis: "Guru", kelas: "—", tujuan: "Membaca", masuk: "08:30", keluar: "09:00", tgl: "02 Jul 2026" },
  { id: 4, nama: "Muhammad Rizki Hidayat", kodeAnggota: "MBR-2026-0146", jenis: "Siswa", kelas: "VII-C", tujuan: "Mengembalikan Buku", masuk: "09:05", keluar: "09:20", tgl: "02 Jul 2026" },
  { id: 5, nama: "Dewi Rahayu", kodeAnggota: "MBR-2026-0145", jenis: "Siswa", kelas: "VIII-A", tujuan: "Tugas", masuk: "09:30", keluar: "10:45", tgl: "02 Jul 2026" },
  { id: 6, nama: "Budi Santoso", kodeAnggota: "MBR-2026-0144", jenis: "Siswa", kelas: "IX-C", tujuan: "Meminjam Buku", masuk: "10:00", keluar: "10:15", tgl: "02 Jul 2026" },
  { id: 7, nama: "Halimah Sadiyah", kodeAnggota: "MBR-2026-0143", jenis: "Siswa", kelas: "VIII-A", tujuan: "Diskusi", masuk: "10:20", keluar: null, tgl: "02 Jul 2026" },
  { id: 8, nama: "Irfan Maulana", kodeAnggota: "MBR-2026-0142", jenis: "Siswa", kelas: "IX-B", tujuan: "Membaca", masuk: "10:35", keluar: null, tgl: "02 Jul 2026" },
  { id: 9, nama: "Tamu Umum", kodeAnggota: null, jenis: "Tamu", kelas: "—", tujuan: "Mencari Referensi", masuk: "11:00", keluar: null, tgl: "02 Jul 2026" },
];

const riwayatPengunjung = [
  { tgl: "01 Jul 2026", total: 41, siswa: 35, guru: 4, tamu: 2 },
  { tgl: "30 Jun 2026", total: 38, siswa: 32, guru: 5, tamu: 1 },
  { tgl: "29 Jun 2026", total: 55, siswa: 49, guru: 5, tamu: 1 },
  { tgl: "28 Jun 2026", total: 47, siswa: 42, guru: 4, tamu: 1 },
  { tgl: "27 Jun 2026", total: 61, siswa: 54, guru: 6, tamu: 1 },
  { tgl: "26 Jun 2026", total: 29, siswa: 25, guru: 3, tamu: 1 },
  { tgl: "25 Jun 2026", total: 44, siswa: 38, guru: 5, tamu: 1 },
];

const tujuanData = [
  { name: "Membaca", value: 38, color: "#145048" },
  { name: "Meminjam Buku", value: 27, color: "#2a8a76" },
  { name: "Tugas", value: 18, color: "#bf7c33" },
  { name: "Mengembalikan", value: 10, color: "#5ba89a" },
  { name: "Diskusi", value: 5, color: "#e8a44a" },
  { name: "Lainnya", value: 2, color: "#a0b0ae" },
];

const jamVisitorData = [
  { jam: "07:00", pengunjung: 4 },
  { jam: "08:00", pengunjung: 12 },
  { jam: "09:00", pengunjung: 9 },
  { jam: "10:00", pengunjung: 11 },
  { jam: "11:00", pengunjung: 7 },
  { jam: "12:00", pengunjung: 2 },
  { jam: "13:00", pengunjung: 6 },
  { jam: "14:00", pengunjung: 5 },
];

// ── PengunjungPage ────────────────────────────────────────────────────────────
function PengunjungPage() {
  const [tab, setTab] = useState<"hari-ini" | "riwayat" | "statistik">("hari-ini");
  const [search, setSearch] = useState("");
  const [showForm, setShowForm] = useState(false);
  const [jenisForm, setJenisForm] = useState<"anggota" | "tamu">("anggota");

  const masihDalam = pengunjungHariIni.filter((v) => v.keluar === null).length;
  const sudahKeluar = pengunjungHariIni.filter((v) => v.keluar !== null).length;

  const filtered = pengunjungHariIni.filter(
    (v) =>
      v.nama.toLowerCase().includes(search.toLowerCase()) ||
      v.tujuan.toLowerCase().includes(search.toLowerCase()) ||
      (v.kodeAnggota ?? "").toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div className="space-y-5">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
            Buku Tamu & Pengunjung
          </h1>
          <p className="text-sm text-muted-foreground mt-0.5">
            Rabu, 02 Juli 2026 — {pengunjungHariIni.length} pengunjung hari ini
          </p>
        </div>
        <button
          onClick={() => setShowForm(true)}
          className="flex items-center gap-2 px-3 py-2 text-sm bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity"
        >
          <Plus size={14} />
          Catat Kunjungan
        </button>
      </div>

      {/* Stat strip */}
      <div className="grid grid-cols-2 gap-3 lg:grid-cols-4">
        {[
          { label: "Total Hari Ini", val: pengunjungHariIni.length.toString(), sub: "02 Jul 2026", icon: <UserCheck size={16} />, color: "bg-[#145048]" },
          { label: "Masih di Dalam", val: masihDalam.toString(), sub: "belum check-out", icon: <Clock size={16} />, color: "bg-[#bf7c33]" },
          { label: "Sudah Keluar", val: sudahKeluar.toString(), sub: "selesai berkunjung", icon: <CheckCircle2 size={16} />, color: "bg-[#2a8a76]" },
          { label: "Rata-rata Kemarin", val: "41", sub: "pengunjung/hari minggu ini", icon: <BarChart2 size={16} />, color: "bg-[#5ba89a]" },
        ].map((c) => (
          <div key={c.label} className="bg-card border border-border rounded-lg p-4 flex items-center gap-3 hover:shadow-sm transition-shadow">
            <div className={`w-9 h-9 rounded-lg flex items-center justify-center text-white shrink-0 ${c.color}`}>
              {c.icon}
            </div>
            <div>
              <div className="text-xl font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>{c.val}</div>
              <div className="text-xs text-muted-foreground leading-tight">{c.label}</div>
              <div className="text-[10px] text-muted-foreground/70">{c.sub}</div>
            </div>
          </div>
        ))}
      </div>

      {/* Tabs */}
      <div className="flex gap-1 bg-muted rounded-lg p-1 w-fit">
        {(["hari-ini", "riwayat", "statistik"] as const).map((t) => (
          <button
            key={t}
            onClick={() => setTab(t)}
            className={`px-4 py-1.5 rounded-md text-xs font-medium transition-colors ${
              tab === t ? "bg-card text-foreground shadow-sm" : "text-muted-foreground hover:text-foreground"
            }`}
          >
            {t === "hari-ini" ? "Hari Ini" : t === "riwayat" ? "Riwayat" : "Statistik"}
          </button>
        ))}
      </div>

      {/* ─── Tab: Hari Ini ─── */}
      {tab === "hari-ini" && (
        <div className="grid grid-cols-1 gap-4 lg:grid-cols-3">
          {/* Main table */}
          <div className="lg:col-span-2 bg-card border border-border rounded-lg overflow-hidden">
            <div className="flex items-center gap-3 px-4 py-3 border-b border-border">
              <div className="flex items-center gap-2 flex-1 bg-input-background border border-border rounded-lg px-3 py-2">
                <Search size={14} className="text-muted-foreground shrink-0" />
                <input
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                  placeholder="Cari nama, tujuan, kode anggota..."
                  className="bg-transparent text-xs outline-none flex-1 text-foreground placeholder:text-muted-foreground"
                />
              </div>
              <button className="flex items-center gap-1.5 text-xs text-muted-foreground hover:text-foreground border border-border rounded-lg px-3 py-2 bg-card hover:bg-secondary transition-colors shrink-0">
                <Download size={12} />
                Ekspor
              </button>
            </div>
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="bg-muted/40">
                    {["#", "Nama Pengunjung", "Jenis", "Tujuan", "Masuk", "Keluar", "Durasi", ""].map((h) => (
                      <th key={h} className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground whitespace-nowrap">{h}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {filtered.map((row, i) => {
                    const durasi = row.keluar
                      ? (() => {
                          const [hm, mm] = row.masuk.split(":").map(Number);
                          const [hk, mk] = row.keluar.split(":").map(Number);
                          const menit = (hk * 60 + mk) - (hm * 60 + mm);
                          return menit >= 60 ? `${Math.floor(menit / 60)}j ${menit % 60}m` : `${menit}m`;
                        })()
                      : null;
                    return (
                      <tr key={row.id} className="border-t border-border hover:bg-muted/20 transition-colors">
                        <td className="px-4 py-3 text-xs text-muted-foreground">{row.id}</td>
                        <td className="px-4 py-3">
                          <div className="flex items-center gap-2">
                            <div className={`w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold text-white shrink-0 ${
                              row.jenis === "Guru" ? "bg-purple-500" : row.jenis === "Tamu" ? "bg-gray-400" : "bg-[#2a8a76]"
                            }`}>
                              {row.nama.charAt(0)}
                            </div>
                            <div>
                              <div className="text-xs font-medium text-foreground">{row.nama}</div>
                              {row.kodeAnggota && (
                                <div className="text-[10px] text-muted-foreground font-mono">{row.kodeAnggota}</div>
                              )}
                            </div>
                          </div>
                        </td>
                        <td className="px-4 py-3">
                          <span className={`inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${
                            row.jenis === "Guru" ? "bg-purple-50 text-purple-700 border border-purple-200"
                              : row.jenis === "Tamu" ? "bg-gray-100 text-gray-600 border border-gray-200"
                              : "bg-sky-50 text-sky-700 border border-sky-200"
                          }`}>{row.jenis}</span>
                        </td>
                        <td className="px-4 py-3 text-xs text-foreground whitespace-nowrap">{row.tujuan}</td>
                        <td className="px-4 py-3 text-xs font-mono text-muted-foreground">{row.masuk}</td>
                        <td className="px-4 py-3">
                          {row.keluar ? (
                            <span className="text-xs font-mono text-muted-foreground">{row.keluar}</span>
                          ) : (
                            <span className="inline-flex items-center gap-1 text-xs text-emerald-600 font-medium">
                              <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse" />
                              Di dalam
                            </span>
                          )}
                        </td>
                        <td className="px-4 py-3 text-xs text-muted-foreground font-mono">
                          {durasi ?? "—"}
                        </td>
                        <td className="px-4 py-3">
                          {!row.keluar && (
                            <button className="text-xs text-primary font-medium hover:underline whitespace-nowrap">
                              Check-out
                            </button>
                          )}
                        </td>
                      </tr>
                    );
                  })}
                </tbody>
              </table>
            </div>
            <div className="px-4 py-3 border-t border-border flex items-center justify-between text-xs text-muted-foreground">
              <span>Menampilkan {filtered.length} dari {pengunjungHariIni.length} pengunjung</span>
              <span className="flex items-center gap-1.5">
                <span className="w-1.5 h-1.5 rounded-full bg-emerald-500" />
                {masihDalam} masih di dalam
              </span>
            </div>
          </div>

          {/* Right: jam & tujuan */}
          <div className="space-y-4">
            {/* Tujuan mini pie */}
            <div className="bg-card border border-border rounded-lg p-4">
              <h3 className="text-xs font-semibold text-foreground mb-3" style={{ fontFamily: "'Roboto Slab', serif" }}>
                Tujuan Kunjungan Hari Ini
              </h3>
              <ResponsiveContainer width="100%" height={130}>
                <PieChart>
                  <Pie data={tujuanData} cx="50%" cy="50%" innerRadius={35} outerRadius={58} paddingAngle={2} dataKey="value">
                    {tujuanData.map((entry) => (
                      <Cell key={`tujuan-${entry.name}`} fill={entry.color} />
                    ))}
                  </Pie>
                  <Tooltip contentStyle={{ borderRadius: "6px", border: "1px solid rgba(0,0,0,0.08)", fontSize: 11 }} />
                </PieChart>
              </ResponsiveContainer>
              <div className="grid grid-cols-2 gap-x-3 gap-y-1 mt-1">
                {tujuanData.map((d) => (
                  <div key={d.name} className="flex items-center justify-between text-[10px]">
                    <span className="flex items-center gap-1 text-muted-foreground">
                      <span className="w-2 h-2 rounded-full shrink-0" style={{ background: d.color }} />
                      {d.name}
                    </span>
                    <span className="font-medium text-foreground">{d.value}%</span>
                  </div>
                ))}
              </div>
            </div>

            {/* Jam sibuk */}
            <div className="bg-card border border-border rounded-lg p-4">
              <h3 className="text-xs font-semibold text-foreground mb-3" style={{ fontFamily: "'Roboto Slab', serif" }}>
                Sebaran Jam Kunjungan
              </h3>
              <ResponsiveContainer width="100%" height={130}>
                <BarChart data={jamVisitorData} barSize={14}>
                  <CartesianGrid strokeDasharray="3 3" stroke="rgba(0,0,0,0.06)" vertical={false} />
                  <XAxis dataKey="jam" tick={{ fontSize: 10, fill: "#6b7a77" }} axisLine={false} tickLine={false} />
                  <YAxis tick={{ fontSize: 10, fill: "#6b7a77" }} axisLine={false} tickLine={false} width={20} />
                  <Tooltip contentStyle={{ borderRadius: "6px", border: "1px solid rgba(0,0,0,0.08)", fontSize: 11 }} cursor={{ fill: "rgba(0,0,0,0.03)" }} />
                  <Bar key="jam-bar" dataKey="pengunjung" name="Pengunjung" fill="#145048" radius={[3, 3, 0, 0]} />
                </BarChart>
              </ResponsiveContainer>
            </div>
          </div>
        </div>
      )}

      {/* ─── Tab: Riwayat ─── */}
      {tab === "riwayat" && (
        <div className="space-y-4">
          <div className="bg-card border border-border rounded-lg p-4">
            <div className="flex items-center justify-between mb-4">
              <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
                Tren Kunjungan 7 Hari Terakhir
              </h2>
              <select className="text-xs px-3 py-1.5 bg-input-background border border-border rounded-lg text-foreground outline-none">
                <option>7 Hari Terakhir</option>
                <option>Bulan Ini</option>
                <option>Bulan Lalu</option>
              </select>
            </div>
            <ResponsiveContainer width="100%" height={180}>
              <AreaChart data={[...riwayatPengunjung].reverse()}>
                <defs>
                  <linearGradient id="visitGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="5%" stopColor="#145048" stopOpacity={0.2} />
                    <stop offset="95%" stopColor="#145048" stopOpacity={0} />
                  </linearGradient>
                </defs>
                <CartesianGrid strokeDasharray="3 3" stroke="rgba(0,0,0,0.06)" vertical={false} />
                <XAxis dataKey="tgl" tick={{ fontSize: 10, fill: "#6b7a77" }} axisLine={false} tickLine={false}
                  tickFormatter={(v) => v.slice(0, 5)} />
                <YAxis tick={{ fontSize: 10, fill: "#6b7a77" }} axisLine={false} tickLine={false} width={28} />
                <Tooltip contentStyle={{ borderRadius: "6px", border: "1px solid rgba(0,0,0,0.08)", fontSize: 11 }} />
                <Area key="visit-area" type="monotone" dataKey="total" name="Total" stroke="#145048" strokeWidth={2} fill="url(#visitGrad)" dot={{ r: 3, fill: "#145048" }} />
              </AreaChart>
            </ResponsiveContainer>
          </div>

          <div className="bg-card border border-border rounded-lg overflow-hidden">
            <div className="flex items-center justify-between px-4 py-3 border-b border-border">
              <h2 className="text-sm font-semibold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
                Detail Per Hari
              </h2>
              <button className="flex items-center gap-1.5 text-xs text-muted-foreground hover:text-foreground border border-border rounded-lg px-3 py-1.5 bg-card hover:bg-secondary transition-colors">
                <Download size={12} />
                Ekspor Excel
              </button>
            </div>
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="bg-muted/40">
                    {["Tanggal", "Total", "Siswa", "Guru", "Tamu", "Grafik"].map((h) => (
                      <th key={h} className="text-left px-4 py-2.5 text-xs font-medium text-muted-foreground">{h}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {riwayatPengunjung.map((row, i) => (
                    <tr key={i} className="border-t border-border hover:bg-muted/20 transition-colors">
                      <td className="px-4 py-3 text-xs font-medium text-foreground">{row.tgl}</td>
                      <td className="px-4 py-3 text-xs font-bold font-mono text-foreground">{row.total}</td>
                      <td className="px-4 py-3 text-xs font-mono text-sky-600">{row.siswa}</td>
                      <td className="px-4 py-3 text-xs font-mono text-purple-600">{row.guru}</td>
                      <td className="px-4 py-3 text-xs font-mono text-gray-500">{row.tamu}</td>
                      <td className="px-4 py-3 w-36">
                        <div className="h-2 bg-muted rounded-full overflow-hidden flex">
                          <div className="bg-sky-400 h-full" style={{ width: `${(row.siswa / row.total) * 100}%` }} />
                          <div className="bg-purple-400 h-full" style={{ width: `${(row.guru / row.total) * 100}%` }} />
                          <div className="bg-gray-300 h-full" style={{ width: `${(row.tamu / row.total) * 100}%` }} />
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
            <div className="flex items-center gap-4 px-4 py-3 border-t border-border text-[10px] text-muted-foreground">
              <span className="flex items-center gap-1"><span className="w-2 h-2 rounded-sm bg-sky-400" /> Siswa</span>
              <span className="flex items-center gap-1"><span className="w-2 h-2 rounded-sm bg-purple-400" /> Guru</span>
              <span className="flex items-center gap-1"><span className="w-2 h-2 rounded-sm bg-gray-300" /> Tamu</span>
            </div>
          </div>
        </div>
      )}

      {/* ─── Tab: Statistik ─── */}
      {tab === "statistik" && (
        <div className="space-y-4">
          <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
            {/* Tujuan kunjungan */}
            <div className="bg-card border border-border rounded-lg p-4">
              <h2 className="text-sm font-semibold text-foreground mb-4" style={{ fontFamily: "'Roboto Slab', serif" }}>
                Distribusi Tujuan Kunjungan
              </h2>
              <div className="flex items-center gap-4">
                <ResponsiveContainer width={140} height={140}>
                  <PieChart>
                    <Pie data={tujuanData} cx="50%" cy="50%" innerRadius={40} outerRadius={65} paddingAngle={2} dataKey="value">
                      {tujuanData.map((entry) => (
                        <Cell key={`stat-tujuan-${entry.name}`} fill={entry.color} />
                      ))}
                    </Pie>
                    <Tooltip contentStyle={{ borderRadius: "6px", border: "1px solid rgba(0,0,0,0.08)", fontSize: 11 }} />
                  </PieChart>
                </ResponsiveContainer>
                <div className="flex-1 space-y-2">
                  {tujuanData.map((d) => (
                    <div key={d.name} className="flex items-center gap-2">
                      <span className="w-2.5 h-2.5 rounded-sm shrink-0" style={{ background: d.color }} />
                      <span className="text-xs text-muted-foreground flex-1">{d.name}</span>
                      <div className="flex items-center gap-2">
                        <div className="w-16 h-1.5 bg-muted rounded-full overflow-hidden">
                          <div className="h-full rounded-full" style={{ width: `${d.value}%`, background: d.color }} />
                        </div>
                        <span className="text-xs font-bold text-foreground w-8 text-right">{d.value}%</span>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>

            {/* Jam tersibuk */}
            <div className="bg-card border border-border rounded-lg p-4">
              <h2 className="text-sm font-semibold text-foreground mb-4" style={{ fontFamily: "'Roboto Slab', serif" }}>
                Jam Tersibuk (Rata-rata)
              </h2>
              <ResponsiveContainer width="100%" height={180}>
                <BarChart data={jamVisitorData}>
                  <CartesianGrid strokeDasharray="3 3" stroke="rgba(0,0,0,0.06)" vertical={false} />
                  <XAxis dataKey="jam" tick={{ fontSize: 11, fill: "#6b7a77" }} axisLine={false} tickLine={false} />
                  <YAxis tick={{ fontSize: 11, fill: "#6b7a77" }} axisLine={false} tickLine={false} width={28} />
                  <Tooltip contentStyle={{ borderRadius: "6px", border: "1px solid rgba(0,0,0,0.08)", fontSize: 11 }} cursor={{ fill: "rgba(0,0,0,0.03)" }} />
                  <Bar key="stat-jam-bar" dataKey="pengunjung" name="Rata-rata" fill="#2a8a76" radius={[3, 3, 0, 0]}>
                    {jamVisitorData.map((entry, index) => (
                      <Cell key={`jam-cell-${index}`} fill={entry.pengunjung === Math.max(...jamVisitorData.map((d) => d.pengunjung)) ? "#145048" : "#5ba89a"} />
                    ))}
                  </Bar>
                </BarChart>
              </ResponsiveContainer>
              <p className="text-[10px] text-muted-foreground mt-2 text-center">Jam 08:00 – 10:00 adalah jam paling ramai</p>
            </div>
          </div>

          {/* Komposisi jenis pengunjung */}
          <div className="bg-card border border-border rounded-lg p-4">
            <h2 className="text-sm font-semibold text-foreground mb-4" style={{ fontFamily: "'Roboto Slab', serif" }}>
              Komposisi Jenis Pengunjung (Bulan Ini)
            </h2>
            <div className="grid grid-cols-3 gap-4">
              {[
                { label: "Siswa", val: 892, pct: 86, color: "#145048", bg: "bg-sky-50 border-sky-200 text-sky-800" },
                { label: "Guru & Staff", val: 118, pct: 11, color: "#2a8a76", bg: "bg-purple-50 border-purple-200 text-purple-800" },
                { label: "Tamu Umum", val: 25, pct: 3, color: "#bf7c33", bg: "bg-orange-50 border-orange-200 text-orange-800" },
              ].map((c) => (
                <div key={c.label} className={`rounded-lg border p-4 ${c.bg}`}>
                  <div className="text-2xl font-bold" style={{ fontFamily: "'Roboto Slab', serif" }}>{c.val}</div>
                  <div className="text-xs font-medium mt-0.5">{c.label}</div>
                  <div className="mt-3 h-1.5 bg-white/50 rounded-full overflow-hidden">
                    <div className="h-full rounded-full" style={{ width: `${c.pct}%`, background: c.color }} />
                  </div>
                  <div className="text-xs font-bold mt-1">{c.pct}%</div>
                </div>
              ))}
            </div>
          </div>

          {/* Summary stats row */}
          <div className="grid grid-cols-2 gap-3 lg:grid-cols-4">
            {[
              { label: "Total Kunjungan Bulan Ini", val: "1.035", sub: "Jul 2026 (berjalan)" },
              { label: "Rata-rata Per Hari", val: "47", sub: "pengunjung/hari" },
              { label: "Hari Tersibuk", val: "Jum'at", sub: "rata-rata 61 pengunjung" },
              { label: "Durasi Rata-rata", val: "38 menit", sub: "per kunjungan" },
            ].map((s) => (
              <div key={s.label} className="bg-card border border-border rounded-lg px-4 py-3">
                <div className="text-base font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>{s.val}</div>
                <div className="text-xs font-medium text-foreground mt-0.5">{s.label}</div>
                <div className="text-[10px] text-muted-foreground mt-0.5">{s.sub}</div>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* ─── Modal: Catat Kunjungan ─── */}
      {showForm && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
          <div className="bg-card rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
            <div className="flex items-center justify-between px-5 py-4 border-b border-border">
              <h2 className="text-sm font-bold text-foreground" style={{ fontFamily: "'Roboto Slab', serif" }}>
                Catat Kunjungan Baru
              </h2>
              <button onClick={() => setShowForm(false)} className="text-muted-foreground hover:text-foreground">
                <X size={16} />
              </button>
            </div>
            <div className="p-5 space-y-4">
              {/* Toggle jenis */}
              <div className="flex gap-1 bg-muted rounded-lg p-1">
                {(["anggota", "tamu"] as const).map((j) => (
                  <button
                    key={j}
                    onClick={() => setJenisForm(j)}
                    className={`flex-1 py-1.5 rounded-md text-xs font-medium transition-colors ${
                      jenisForm === j ? "bg-card text-foreground shadow-sm" : "text-muted-foreground hover:text-foreground"
                    }`}
                  >
                    {j === "anggota" ? "Anggota Perpustakaan" : "Tamu Umum"}
                  </button>
                ))}
              </div>

              {jenisForm === "anggota" ? (
                <div className="space-y-3">
                  <div className="space-y-1">
                    <label className="text-xs font-medium text-foreground">Cari Anggota</label>
                    <div className="flex items-center gap-2 bg-input-background border border-border rounded-lg px-3 py-2">
                      <Search size={13} className="text-muted-foreground shrink-0" />
                      <input
                        placeholder="Ketik nama atau NIS..."
                        className="bg-transparent text-xs outline-none flex-1 text-foreground placeholder:text-muted-foreground"
                      />
                    </div>
                  </div>
                  {/* Mock search result */}
                  <div className="border border-border rounded-lg divide-y divide-border bg-input-background">
                    {[
                      { nama: "Ahmad Fauzi Ramadhan", kode: "MBR-2026-0148", kelas: "VIII-B" },
                      { nama: "Ahmad Fauzan", kode: "MBR-2026-0139", kelas: "VII-A" },
                    ].map((a) => (
                      <button key={a.kode} className="w-full text-left px-3 py-2 flex items-center gap-2.5 hover:bg-secondary/50 transition-colors">
                        <div className="w-6 h-6 rounded-full bg-[#2a8a76] flex items-center justify-center text-white text-[10px] font-bold shrink-0">
                          {a.nama.charAt(0)}
                        </div>
                        <div>
                          <div className="text-xs font-medium text-foreground">{a.nama}</div>
                          <div className="text-[10px] text-muted-foreground">{a.kode} · {a.kelas}</div>
                        </div>
                      </button>
                    ))}
                  </div>
                </div>
              ) : (
                <div className="space-y-3">
                  <div className="space-y-1">
                    <label className="text-xs font-medium text-foreground">Nama Tamu</label>
                    <input
                      placeholder="Nama lengkap tamu..."
                      className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring placeholder:text-muted-foreground"
                    />
                  </div>
                  <div className="space-y-1">
                    <label className="text-xs font-medium text-foreground">Instansi / Asal</label>
                    <input
                      placeholder="Opsional..."
                      className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring placeholder:text-muted-foreground"
                    />
                  </div>
                </div>
              )}

              <div className="space-y-1">
                <label className="text-xs font-medium text-foreground">Tujuan Kunjungan</label>
                <select className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring">
                  {["Membaca", "Meminjam Buku", "Mengembalikan Buku", "Tugas", "Diskusi", "Mencari Referensi", "Lainnya"].map((t) => (
                    <option key={t}>{t}</option>
                  ))}
                </select>
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div className="space-y-1">
                  <label className="text-xs font-medium text-foreground">Jam Masuk</label>
                  <input
                    type="time"
                    defaultValue={new Date().toTimeString().slice(0, 5)}
                    className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring"
                  />
                </div>
                <div className="space-y-1">
                  <label className="text-xs font-medium text-foreground">Tanggal</label>
                  <input
                    type="date"
                    defaultValue="2026-07-02"
                    className="w-full text-xs px-3 py-2 bg-input-background border border-border rounded-lg text-foreground outline-none focus:ring-2 focus:ring-ring"
                  />
                </div>
              </div>
            </div>
            <div className="flex gap-2 px-5 pb-5">
              <button
                onClick={() => setShowForm(false)}
                className="flex-1 py-2 text-xs font-medium bg-card border border-border rounded-lg hover:bg-secondary transition-colors text-foreground"
              >
                Batal
              </button>
              <button
                onClick={() => setShowForm(false)}
                className="flex-1 flex items-center justify-center gap-1.5 py-2 text-xs font-medium bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition-opacity"
              >
                <CheckCircle2 size={13} />
                Simpan Kunjungan
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

function PlaceholderPage({ title, desc }: { title: string; desc: string }) {
  return (
    <div className="flex flex-col items-center justify-center h-64 text-center">
      <div className="w-12 h-12 rounded-full bg-muted flex items-center justify-center mb-4">
        <ClipboardList size={20} className="text-muted-foreground" />
      </div>
      <h2 className="text-base font-semibold text-foreground mb-1" style={{ fontFamily: "'Roboto Slab', serif" }}>{title}</h2>
      <p className="text-sm text-muted-foreground">{desc}</p>
    </div>
  );
}

// ── App ───────────────────────────────────────────────────────────────────────
export default function App() {
  const [activePage, setActivePage] = useState<Page>("dashboard");
  const [expandedGroups, setExpandedGroups] = useState<Set<string>>(new Set(["buku"]));
  const [sidebarOpen, setSidebarOpen] = useState(true);

  const toggleGroup = (id: string) => {
    setExpandedGroups((prev) => {
      const next = new Set(prev);
      next.has(id) ? next.delete(id) : next.add(id);
      return next;
    });
  };

  const renderPage = () => {
    switch (activePage) {
      case "dashboard": return <DashboardPage />;
      case "buku-master": return <BukuPage />;
      case "anggota": return <AnggotaPage />;
      case "peminjaman": return <PeminjamanPage />;
      case "pengunjung": return <PengunjungPage />;
      case "buku-eksemplar": return <EksemplarPage />;
      case "pengadaan": return <PengadaanPage />;
      case "insiden": return <InsidenPage />;
      case "stok-opname": return <StokOpnamePage />;
      case "pengembalian": return <PengembalianPage />;
      case "laporan": return <LaporanPage />;
      case "pengaturan": return <PengaturanPage />;
      default: return <PlaceholderPage title="Halaman dalam Pengembangan" desc="Fitur ini akan segera tersedia." />;
    }
  };

  return (
    <div className="flex h-screen overflow-hidden bg-background" style={{ fontFamily: "'Inter', sans-serif" }}>
      {/* Sidebar */}
      <aside
        className={`flex flex-col shrink-0 transition-all duration-300 overflow-hidden ${sidebarOpen ? "w-56" : "w-0"}`}
        style={{ background: "var(--sidebar)" }}
      >
        {/* Logo */}
        <div className="flex items-center gap-2.5 px-4 py-4 border-b" style={{ borderColor: "var(--sidebar-border)" }}>
          <div className="w-8 h-8 rounded-lg bg-[#bf7c33] flex items-center justify-center text-white">
            <BookOpen size={16} />
          </div>
          <div>
            <div className="text-sm font-bold text-white leading-tight" style={{ fontFamily: "'Roboto Slab', serif" }}>SIPUS</div>
            <div className="text-[10px] font-medium leading-tight" style={{ color: "var(--sidebar-foreground)", opacity: 0.6 }}>Al-Ihsan Batujajar</div>
          </div>
        </div>

        {/* Nav */}
        <nav className="flex-1 overflow-y-auto py-3 px-2 space-y-4 scrollbar-none">
          {navGroups.map((group) => (
            <div key={group.label}>
              <div className="px-2 mb-1 text-[10px] font-semibold uppercase tracking-widest" style={{ color: "var(--sidebar-foreground)", opacity: 0.4 }}>
                {group.label}
              </div>
              {group.items.map((item) => {
                const isActive = activePage === item.id || item.children?.some((c) => c.id === activePage);
                const isExpanded = expandedGroups.has(item.id);
                return (
                  <div key={item.id}>
                    <button
                      onClick={() => {
                        if (item.children) {
                          toggleGroup(item.id);
                        } else {
                          setActivePage(item.id);
                        }
                      }}
                      className={`w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs font-medium transition-colors ${
                        isActive
                          ? "text-white"
                          : "hover:text-white"
                      }`}
                      style={{
                        background: isActive ? "var(--sidebar-accent)" : "transparent",
                        color: isActive ? "white" : "var(--sidebar-foreground)",
                        opacity: isActive ? 1 : 0.75,
                      }}
                    >
                      <span className="shrink-0">{item.icon}</span>
                      <span className="flex-1 text-left">{item.label}</span>
                      {item.badge != null && item.badge > 0 && (
                        <span className="bg-red-500 text-white text-[9px] font-bold rounded-full px-1.5 py-0.5 leading-none">
                          {item.badge}
                        </span>
                      )}
                      {item.children && (
                        <ChevronDown
                          size={12}
                          className="transition-transform"
                          style={{ transform: isExpanded ? "rotate(0deg)" : "rotate(-90deg)" }}
                        />
                      )}
                    </button>
                    {item.children && isExpanded && (
                      <div className="ml-6 mt-0.5 space-y-0.5 border-l pl-3" style={{ borderColor: "var(--sidebar-border)" }}>
                        {item.children.map((child) => (
                          <button
                            key={child.id}
                            onClick={() => setActivePage(child.id)}
                            className="w-full text-left px-2 py-1.5 rounded text-xs transition-colors"
                            style={{
                              color: activePage === child.id ? "white" : "var(--sidebar-foreground)",
                              opacity: activePage === child.id ? 1 : 0.65,
                              background: activePage === child.id ? "var(--sidebar-accent)" : "transparent",
                            }}
                          >
                            {child.label}
                          </button>
                        ))}
                      </div>
                    )}
                  </div>
                );
              })}
            </div>
          ))}
        </nav>

        {/* User */}
        <div className="px-3 py-3 border-t" style={{ borderColor: "var(--sidebar-border)" }}>
          <div className="flex items-center gap-2.5">
            <div className="w-7 h-7 rounded-full bg-[#bf7c33] flex items-center justify-center text-white text-xs font-bold shrink-0">A</div>
            <div className="flex-1 min-w-0">
              <div className="text-xs font-medium text-white truncate">Admin Perpustakaan</div>
              <div className="text-[10px]" style={{ color: "var(--sidebar-foreground)", opacity: 0.55 }}>Administrator</div>
            </div>
            <button className="text-white/50 hover:text-white transition-colors"><LogOut size={13} /></button>
          </div>
        </div>
      </aside>

      {/* Main */}
      <div className="flex-1 flex flex-col overflow-hidden min-w-0">
        {/* Top bar */}
        <header className="shrink-0 flex items-center gap-3 px-4 py-3 bg-card border-b border-border">
          <button
            onClick={() => setSidebarOpen((v) => !v)}
            className="text-muted-foreground hover:text-foreground transition-colors"
          >
            {sidebarOpen ? <X size={18} /> : <Menu size={18} />}
          </button>

          {/* Breadcrumb */}
          <div className="flex items-center gap-1 text-sm text-muted-foreground">
            <span className="text-foreground font-medium capitalize">
              {activePage.replace(/-/g, " ")}
            </span>
          </div>

          {/* Global search */}
          <div className="flex items-center gap-2 flex-1 max-w-xs mx-auto bg-input-background border border-border rounded-lg px-3 py-1.5">
            <Search size={14} className="text-muted-foreground" />
            <input
              placeholder="Cari buku, anggota, transaksi..."
              className="bg-transparent text-sm outline-none flex-1 text-foreground placeholder:text-muted-foreground"
            />
          </div>

          <div className="ml-auto flex items-center gap-2">
            <button className="relative w-8 h-8 flex items-center justify-center rounded-lg hover:bg-muted transition-colors text-muted-foreground hover:text-foreground">
              <Bell size={16} />
              <span className="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full" />
            </button>
            <div className="text-right hidden sm:block">
              <div className="text-xs font-medium text-foreground">MTs Al-Ihsan</div>
              <div className="text-[10px] text-muted-foreground">Batujajar, Bandung Barat</div>
            </div>
          </div>
        </header>

        {/* Page content */}
        <main className="flex-1 overflow-y-auto p-5">
          {renderPage()}
        </main>
      </div>
    </div>
  );
}
