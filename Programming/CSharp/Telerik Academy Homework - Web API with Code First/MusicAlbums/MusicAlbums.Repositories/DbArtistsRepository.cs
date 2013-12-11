namespace MusicAlbums.Repositories
{
    using MusicAlbums.Model;
    using System;
    using System.Data.Entity;
    using System.Linq;
    using System.Linq.Expressions;

    public class DbArtistsRepository : IRepository<Artist>
    {
        private const string EntityName = "Artist";

        private DbContext dbContext;
        private DbSet<Artist> entitySet;

        public DbArtistsRepository(DbContext dbContext)
        {
            this.dbContext = dbContext;
            this.entitySet = this.dbContext.Set<Artist>();
        }

        public Artist Add(Artist item)
        {
            if (item == null || item.Name == null)
            {
                string exceptionMessage = string.Format("The passed {0} or its name should not be null.", EntityName);
                throw new ArgumentNullException(exceptionMessage);
            }

            this.entitySet.Add(item);
            this.dbContext.SaveChanges();
            return item;
        }

        public Artist Get(int id)
        {
            return this.entitySet.Find(id);
        }

        public IQueryable<Artist> GetAll()
        {
            return this.entitySet;
        }

        public IQueryable<Artist> GetAllFull()
        {
            string exceptionMessage = string.Format("The {0} repository doesn't have GetAllFull method.", EntityName);
            throw new NotImplementedException(exceptionMessage);
        }

        public IQueryable<Artist> Find(Expression<Func<Artist, int, bool>> predicate)
        {
            return this.entitySet.Where(predicate);
        }

        public Artist Update(int id, Artist item)
        {
            if (item == null || item.Name == null)
            {
                string exceptionMessage = string.Format("The passed {0} or its name should not be null.", EntityName);
                throw new ArgumentNullException(exceptionMessage);
            }

            if (!id.Equals(item.Id))
            {
                string exceptionMessage = string.Format("The passed Id and the passed {0} Id should be equal.", EntityName);
                throw new ArgumentException(exceptionMessage);
            }

            var oldItem = this.entitySet.Find(id);
            oldItem = item;

            // this.dbContext.Entry(item).State = EntityState.Modified;

            // this.entitySet.Attach(item);
            // this.dbContext.SaveChanges();
            return item;
        }

        public Artist Delete(int id)
        {
            var item = this.entitySet.Find(id);
            if (item == null)
            {
                string exceptionMessage = string.Format("No {0} found with this Id.", EntityName);
                throw new NullReferenceException(exceptionMessage);
            }

            this.entitySet.Remove(item);
            return item;
        }

        public Artist Delete(Artist item)
        {
            if (item == null)
            {
                string exceptionMessage = string.Format("The passed {0} should not be null.", EntityName);
                throw new ArgumentNullException(exceptionMessage);
            }

            this.entitySet.Remove(item);
            return item;
        }


        public Artist AddAndSave(Artist item)
        {
            var addedItem = Add(item);
            SaveChanges();

            return addedItem;
        }

        public Artist UpdateAndSave(int id, Artist item)
        {
            var newItem = Update(id, item);
            SaveChanges();

            return newItem;
        }

        public Artist DeleteAndSave(int id)
        {
            var deletedItem = Delete(id);
            SaveChanges();
            return deletedItem;
        }

        public Artist DeleteAndSave(Artist item)
        {
            var deletedItem = Delete(item);
            SaveChanges();
            return deletedItem;
        }


        public void Dispose()
        {
            this.entitySet = null;
            this.dbContext.Dispose();
        }

        public void SaveChanges()
        {
            this.dbContext.SaveChanges();
        }
    }
}
