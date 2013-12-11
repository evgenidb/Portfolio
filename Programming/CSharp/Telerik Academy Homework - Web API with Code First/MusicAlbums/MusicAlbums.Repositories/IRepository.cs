namespace MusicAlbums.Repositories
{
    using System;
    using System.Linq;
    using System.Linq.Expressions;

    public interface IRepository<T>
    {
        T Add(T item);
        T AddAndSave(T item);

        T Get(int id);
        IQueryable<T> GetAll();
        IQueryable<T> GetAllFull();
        IQueryable<T> Find(Expression<Func<T, int, bool>> predicate);

        T Update(int id, T item);
        T UpdateAndSave(int id, T item);

        T Delete(int id);
        T Delete(T item);
        T DeleteAndSave(int id);
        T DeleteAndSave(T item);

        void Dispose();
        void SaveChanges();
    }
}
